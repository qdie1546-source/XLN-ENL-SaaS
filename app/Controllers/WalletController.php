<?php

namespace App\Controllers;

use LinkHub\Models\Wallet;
use LinkHub\Models\Transaction;
use LinkHub\Models\PaymentRequest;
use App\Libraries\Epay;
use App\Libraries\Config;
use App\Libraries\Database;

class WalletController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        $walletModel = new Wallet();
        $wallet = $walletModel->getByUser($user['id']);

        $txnModel = new Transaction();
        $transactions = $txnModel->byUser($user['id'], 50);

        $this->view('wallet/index', [
            'title' => '我的钱包',
            'section' => 'wallet',
            'balance' => floatval($wallet['balance'] ?? 0),
            'transactions' => $transactions,
        ]);
    }

    public function deposit()
    {
        $this->requireAuth();
        $user = $this->auth();

        $amount = floatval($_POST['amount'] ?? 0);
        if ($amount <= 0) {
            $_SESSION['flash']['error'] = '请输入有效的充值金额';
            $this->redirect('/wallet');
        }

        $config = $this->loadEpayConfig();
        if (!empty($config['epay_enabled']) && $this->isEpayConfigured($config)) {
            $paymentRequest = (new PaymentRequest())->createRequest($user['id'], 'wallet', null, $amount, '钱包充值', [
                'reason' => 'wallet_deposit',
            ]);

            $params = [
                'pid' => $config['epay_pid'],
                'type' => 'alipay',
                'notify_url' => url('/payment/notify'),
                'return_url' => url('/payment/return'),
                'out_trade_no' => $paymentRequest['out_trade_no'],
                'name' => '钱包充值',
                'money' => number_format($amount, 2, '.', ''),
                'param' => 'order_type=wallet',
                'sign_type' => 'MD5',
                'key' => $config['epay_key'],
            ];

            header('Location: ' . Epay::buildPaymentUrl($params, $config['epay_api_url']));
            exit;
        }

        $walletModel = new Wallet();
        $walletModel->addBalance($user['id'], $amount);

        $txnModel = new Transaction();
        $txnModel->recordTransaction($user['id'], 'deposit', $amount, null, "钱包充值 +{$amount} 元");

        $_SESSION['flash']['success'] = "成功充值 {$amount} 元";
        $this->redirect('/wallet');
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

    public function withdraw()
    {
        $this->requireAuth();
        $user = $this->auth();

        $amount = floatval($_POST['amount'] ?? 0);
        if ($amount <= 0) {
            $_SESSION['flash']['error'] = '请输入有效的提现金额';
            $this->redirect('/wallet');
        }

        $walletModel = new Wallet();
        $result = $walletModel->deductBalance($user['id'], $amount);

        if ($result === false) {
            $_SESSION['flash']['error'] = '余额不足';
            $this->redirect('/wallet');
        }

        $txnModel = new Transaction();
        $txnModel->recordTransaction($user['id'], 'withdraw', -$amount, null, "钱包提现 -{$amount} 元");

        $_SESSION['flash']['success'] = "已提交提现申请: {$amount} 元，等待处理";
        $this->redirect('/wallet');
    }
}
