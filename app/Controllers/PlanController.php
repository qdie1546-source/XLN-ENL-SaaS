<?php

namespace App\Controllers;

use LinkHub\Models\Plan;
use LinkHub\Models\PlanPurchase;
use LinkHub\Models\Wallet;
use LinkHub\Models\Transaction;

class PlanController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        $planModel = new Plan();
        $plans = $planModel->active();

        $purchaseModel = new PlanPurchase();
        $myPurchases = $purchaseModel->byUser($user['id']);

        $walletModel = new Wallet();
        $wallet = $walletModel->getByUser($user['id']);

        $this->view('plans/index', [
            'title' => '套餐中心',
            'currentSection' => 'plans',
            'plans' => $plans,
            'myPurchases' => $myPurchases,
            'wallet' => $wallet,
        ]);
    }

    public function purchase($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        $db = \App\Libraries\Database::getInstance();
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');

        $plan = $db->fetch("SELECT * FROM `{$prefix}plans` WHERE `id` = ? AND `is_active` = 1", [$id]);
        if (!$plan) {
            $_SESSION['flash']['error'] = '套餐不存在或已下架';
            $this->redirect('/plans');
        }

        $price = floatval($plan['price']);

        if ($price > 0) {
            $walletModel = new Wallet();
            $txnModel = new Transaction();

            if (!$walletModel->deductBalance($user['id'], $price)) {
                $_SESSION['flash']['error'] = '余额不足，请先充值';
                $this->redirect('/plans');
            }

            $txnModel->recordTransaction($user['id'], 'purchase', -$price, $id, "购买套餐：{$plan['name']}");
            $txnModel->recordTransaction(0, 'commission', $price, $id, "套餐售出佣金：{$plan['name']}");
        }

        // Record plan purchase
        $expiresAt = null;
        if ($plan['duration_days'] > 0) {
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$plan['duration_days']} days"));
        }

        $db->query(
            "INSERT INTO `{$prefix}plan_purchases` (`user_id`, `plan_id`, `price_paid`, `expires_at`, `created_at`) VALUES (?, ?, ?, ?, ?)",
            [$user['id'], $id, $price, $expiresAt, date('Y-m-d H:i:s')]
        );

        // If enterprise plan, upgrade user
        if ($plan['type'] === 'enterprise') {
            $db->query(
                "INSERT OR IGNORE INTO `{$prefix}enterprise_profiles` (`user_id`, `company_name`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?)",
                [$user['id'], '', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]
            );
            $db->query("UPDATE `{$prefix}users` SET `user_type` = 'enterprise' WHERE `id` = ?", [$user['id']]);
        }

        $_SESSION['flash']['success'] = '套餐购买成功！';
        $this->redirect('/plans');
    }
}
