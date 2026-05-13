<?php

namespace App\Controllers;

use App\Libraries\Epay;
use LinkHub\Models\PaymentRequest;

class CheckoutController extends Controller
{
    public function index()
    {
        $this->requireAuth();

        $type = $_GET['type'] ?? '';
        $id = intval($_GET['id'] ?? 0);

        if (empty($type) || $id <= 0) {
            $_SESSION['flash']['error'] = '无效的订单信息';
            $this->redirect('/dashboard');
        }

        $db = \App\Libraries\Database::getInstance();
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');

        $item = null;
        $price = 0;
        $itemName = '';

        if ($type === 'plan') {
            $item = $db->fetch("SELECT * FROM `{$prefix}plans` WHERE `id` = ? AND `is_active` = 1", [$id]);
            if ($item) {
                $price = floatval($item['price'] ?? 0);
                $itemName = $item['name'];
            }
        } elseif ($type === 'theme') {
            $item = $db->fetch("SELECT * FROM `{$prefix}user_themes` WHERE `id` = ? AND `status` = 'approved' AND `is_active` = 1", [$id]);
            if ($item) {
                $price = floatval($item['price'] ?? 0);
                $itemName = $item['name'];
            }
        }

        if (!$item) {
            $_SESSION['flash']['error'] = '商品不存在或已下架';
            $this->redirect('/dashboard');
        }

        if ($price <= 0) {
            $_SESSION['flash']['error'] = '免费商品无需支付';
            $this->redirect('/dashboard');
        }

        // Get payment configs
        $configs = $db->fetchAll("SELECT `key`, `value` FROM `{$prefix}config`");
        $cfg = [];
        foreach ($configs as $c) {
            $cfg[$c['key']] = $c['value'];
        }

        $this->view('checkout/index', [
            'title' => '收银台',
            'section' => 'checkout',
            'orderType' => $type,
            'orderId' => $id,
            'itemName' => $itemName,
            'price' => $price,
            'config' => $cfg,
        ]);
    }

    public function process()
    {
        $this->requireAuth();
        $user = $this->auth();

        $type = $_POST['type'] ?? '';
        $id = intval($_POST['id'] ?? 0);
        $paymentMethod = $_POST['payment_method'] ?? '';

        if (empty($type) || $id <= 0 || empty($paymentMethod)) {
            $_SESSION['flash']['error'] = '无效的支付请求';
            $this->redirect('/dashboard');
        }

        if ($paymentMethod === 'epay') {
            $config = $this->loadEpayConfig();
            if (empty($config['epay_enabled']) || !$this->isEpayConfigured($config)) {
                $_SESSION['flash']['error'] = '易支付未配置，请联系管理员。';
                $this->redirect('/dashboard');
            }

            $title = $itemName ? "购买：{$itemName}" : '在线支付';
            $paymentRequest = (new PaymentRequest())->createRequest($user['id'], $type, $id, $price, $title, [
                'item_name' => $itemName,
            ]);

            $params = [
                'pid' => $config['epay_pid'],
                'type' => 'alipay',
                'notify_url' => url('/payment/notify'),
                'return_url' => url('/payment/return'),
                'out_trade_no' => $paymentRequest['out_trade_no'],
                'name' => $title,
                'money' => number_format($price, 2, '.', ''),
                'param' => "order_type={$type}&order_id={$id}",
                'sign_type' => 'MD5',
                'key' => $config['epay_key'],
            ];

            header('Location: ' . Epay::buildPaymentUrl($params, $config['epay_api_url']));
            exit;
        }

        if ($type === 'plan') {
            $planController = new PlanController();
            $planController->purchase($id);
            return;
        } elseif ($type === 'theme') {
            $marketController = new ThemeMarketController();
            $marketController->purchase($id);
            return;
        }

        $this->redirect('/dashboard');
    }

    private function loadEpayConfig(): array
    {
        $db = \App\Libraries\Database::getInstance();
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
        $configs = $db->fetchAll("SELECT `key`, `value` FROM `{$prefix}config`");
        $result = [];
        foreach ($configs as $config) {
            $result[$config['key']] = $config['value'];
        }
        return $result;
    }

    private function isEpayConfigured(array $config): bool
    {
        return !empty($config['epay_pid']) && !empty($config['epay_key']) && !empty($config['epay_api_url']);
    }
}
