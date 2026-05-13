<?php

namespace App\Controllers;

use App\Libraries\Config;
use App\Libraries\Database;
use App\Libraries\Epay;
use LinkHub\Models\PaymentRequest;
use LinkHub\Models\PlanPurchase;
use LinkHub\Models\ThemePurchase;
use LinkHub\Models\UserTheme;
use LinkHub\Models\Transaction;
use LinkHub\Models\Wallet;

class PaymentController extends Controller
{
    public function notify()
    {
        $params = $this->getRequestParams();
        if (!$this->isValidEpayRequest($params)) {
            echo 'error';
            return;
        }

        $request = (new PaymentRequest())->findByOutTradeNo($params['out_trade_no'] ?? '');
        if (!$request) {
            echo 'error';
            return;
        }

        if ($request['status'] !== 'pending') {
            echo 'success';
            return;
        }

        if (!Epay::isSuccessStatus($params['trade_status'] ?? '')) {
            echo 'error';
            return;
        }

        $completed = $this->completePaymentRequest($request, $params);
        echo $completed ? 'success' : 'error';
    }

    public function return()
    {
        $params = $this->getRequestParams();

        if ($this->isValidEpayRequest($params) && Epay::isSuccessStatus($params['trade_status'] ?? '')) {
            $_SESSION['flash']['success'] = '支付成功，订单已确认。';
        } else {
            $_SESSION['flash']['error'] = '支付结果验证失败，请联系客服。';
        }

        $pay = (new PaymentRequest())->findByOutTradeNo($params['out_trade_no'] ?? '');
        if ($pay && $pay['order_type'] === 'wallet') {
            $this->redirect('/wallet');
            return;
        }

        $this->redirect('/dashboard');
    }

    private function getRequestParams(): array
    {
        return array_merge($_GET, $_POST);
    }

    private function isValidEpayRequest(array $params): bool
    {
        $config = $this->loadEpayConfig();
        if (empty($config['epay_enabled']) || !$this->isEpayConfigured($config)) {
            return false;
        }

        if (empty($params['pid']) || empty($params['sign']) || empty($params['sign_type']) || empty($params['out_trade_no'])) {
            return false;
        }

        if ($params['pid'] !== $config['epay_pid']) {
            return false;
        }

        return Epay::verifySignature($params, $config['epay_key']);
    }

    private function loadEpayConfig(): array
    {
        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');
        $rows = $db->fetchAll("SELECT `key`, `value` FROM `{$prefix}config`");
        $config = [];
        foreach ($rows as $row) {
            $config[$row['key']] = $row['value'];
        }
        return $config;
    }

    private function isEpayConfigured(array $config): bool
    {
        return !empty($config['epay_pid']) && !empty($config['epay_key']) && !empty($config['epay_api_url']);
    }

    private function completePaymentRequest(array $request, array $params): bool
    {
        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');
        $userId = intval($request['user_id']);
        $amount = floatval($request['amount']);
        $orderType = $request['order_type'];
        $orderId = intval($request['order_id']);
        $title = $request['title'] ?? '';
        $tradeNo = $params['trade_no'] ?? '';

        if ($orderType === 'wallet') {
            $walletModel = new Wallet();
            $walletModel->addBalance($userId, $amount);

            $txn = new Transaction();
            $txn->recordTransaction($userId, 'deposit', $amount, $request['id'], "钱包充值 +{$amount} 元");
        } elseif ($orderType === 'plan') {
            $plan = $db->fetch("SELECT * FROM `{$prefix}plans` WHERE `id` = ? AND `is_active` = 1", [$orderId]);
            if (!$plan) {
                return false;
            }

            $existing = $db->fetch("SELECT COUNT(*) AS cnt FROM `{$prefix}plan_purchases` WHERE `user_id` = ? AND `plan_id` = ?", [$userId, $orderId]);
            if (intval($existing['cnt'] ?? 0) === 0) {
                $expiresAt = null;
                if ($plan['duration_days'] > 0) {
                    $expiresAt = date('Y-m-d H:i:s', strtotime("+{$plan['duration_days']} days"));
                }
                (new PlanPurchase())->create([
                    'user_id' => $userId,
                    'plan_id' => $orderId,
                    'price_paid' => $amount,
                    'expires_at' => $expiresAt,
                ]);
            }

            $txn = new Transaction();
            $txn->recordTransaction($userId, 'purchase', -$amount, $orderId, "购买套餐：{$plan['name']}");
            $txn->recordTransaction(0, 'commission', $amount, $orderId, "套餐售出佣金：{$plan['name']}");
        } elseif ($orderType === 'theme') {
            $theme = (new UserTheme())->find($orderId);
            if (!$theme || $theme['status'] !== 'approved') {
                return false;
            }

            if ($theme['user_id'] === $userId) {
                return false;
            }

            $purchaseModel = new ThemePurchase();
            if (!$purchaseModel->hasPurchased($userId, $orderId)) {
                $purchaseModel->create([
                    'buyer_id' => $userId,
                    'theme_id' => $orderId,
                    'price_paid' => $amount,
                ]);

                if ($amount > 0) {
                    $walletModel = new Wallet();
                    $txn = new Transaction();

                    $commissionRate = floatval($db->fetch("SELECT `value` FROM `{$prefix}config` WHERE `key` = 'commission_rate'", [])["value"] ?? 20) / 100;
                    $commission = round($amount * $commissionRate, 2);
                    $sellerAmount = $amount - $commission;

                    if ($sellerAmount > 0) {
                        $walletModel->addBalance($theme['user_id'], $sellerAmount);
                        $txn->recordTransaction($theme['user_id'], 'sale', $sellerAmount, $orderId, "主题售出：{$theme['name']}");
                    }
                    $txn->recordTransaction(0, 'commission', $commission, $orderId, "平台佣金：{$theme['name']}");
                }

                (new Transaction())->recordTransaction($userId, 'purchase', -$amount, $orderId, "购买主题：{$theme['name']}");
            }
        }

        (new PaymentRequest())->markCompleted($request['id'], $tradeNo);
        return true;
    }
}
