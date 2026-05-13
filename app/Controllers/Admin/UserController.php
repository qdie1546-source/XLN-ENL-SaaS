<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use LinkHub\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $userModel = new User();
        $users = $userModel->all();

        // Fetch wallet balances
        $db = \App\Libraries\Database::getInstance();
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
        $wallets = $db->fetchAll("SELECT * FROM `{$prefix}wallets`");
        $balanceMap = [];
        foreach ($wallets as $w) {
            $balanceMap[$w['user_id']] = floatval($w['balance']);
        }
        foreach ($users as &$u) {
            $u['balance'] = $balanceMap[$u['id']] ?? 0.00;
        }

        $this->view('admin/users', [
            'users' => $users,
            'currentSection' => 'users'
        ]);
    }

    public function edit($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $userModel = new User();
        $targetUser = $userModel->find($id);

        if (!$targetUser) {
            $_SESSION['flash']['error'] = '用户不存在';
            $this->redirect('/admin/users');
        }

        $db = \App\Libraries\Database::getInstance();
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
        $wallet = $db->fetch("SELECT * FROM `{$prefix}wallets` WHERE `user_id` = ?", [$id]);
        $targetUser['balance'] = floatval($wallet['balance'] ?? 0);

        $this->view('admin/user-edit', [
            'user' => $targetUser,
            'currentSection' => 'users'
        ]);
    }

    public function update($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $userModel = new User();
        $targetUser = $userModel->find($id);

        if (!$targetUser) {
            $_SESSION['flash']['error'] = '用户不存在';
            $this->redirect('/admin/users');
        }

        $email = $this->getPost('email');
        $name = $this->getPost('name', $targetUser['name'] ?? '');
        $phone = $this->getPost('phone', $targetUser['phone'] ?? '');
        $isAdmin = $this->getPost('is_admin') ? 1 : 0;
        $isActive = $this->getPost('is_active') ? 1 : 0;

        if (empty($email)) {
            $_SESSION['flash']['error'] = '请填写邮箱';
            $this->back();
        }

        $updateData = [
            'email' => $email,
            'name' => $name,
            'phone' => $phone ?: null,
            'is_admin' => $isAdmin,
            'is_active' => $isActive,
        ];

        // Password reset
        $newPassword = $this->getPost('new_password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                $_SESSION['flash']['error'] = '新密码长度至少6位';
                $this->back();
            }
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userModel->update($id, $updateData);

        // Handle balance adjustment
        $balanceAdjust = floatval($this->getPost('balance_adjust', '0'));
        if ($balanceAdjust != 0) {
            $db = \App\Libraries\Database::getInstance();
            $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
            $existing = $db->fetch("SELECT * FROM `{$prefix}wallets` WHERE `user_id` = ?", [$id]);
            if ($existing) {
                $newBalance = floatval($existing['balance']) + $balanceAdjust;
                $db->query("UPDATE `{$prefix}wallets` SET `balance` = ?, `updated_at` = ? WHERE `user_id` = ?", [$newBalance, date('Y-m-d H:i:s'), $id]);
            } else {
                $db->query("INSERT INTO `{$prefix}wallets` (`user_id`, `balance`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?)", [$id, max(0, $balanceAdjust), date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            }
            $txnModel = new \LinkHub\Models\Transaction();
            $txnModel->recordTransaction($id, $balanceAdjust > 0 ? 'deposit' : 'withdraw', abs($balanceAdjust), null, $balanceAdjust > 0 ? '管理员调整 +' . $balanceAdjust : '管理员调整 ' . $balanceAdjust);
        }

        $_SESSION['flash']['success'] = '用户信息已更新';
        $this->redirect('/admin/users');
    }

    public function delete($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $userModel = new User();
        $targetUser = $userModel->find($id);

        if (!$targetUser) {
            $_SESSION['flash']['error'] = '用户不存在';
            $this->redirect('/admin/users');
        }

        // Don't allow deleting yourself
        if ($id == $user['id']) {
            $_SESSION['flash']['error'] = '不能删除自己的账号';
            $this->redirect('/admin/users');
        }

        $userModel->delete($id);

        $_SESSION['flash']['success'] = '用户「' . htmlspecialchars($targetUser['name'] ?? $targetUser['email']) . '」已删除';
        $this->redirect('/admin/users');
    }
}
