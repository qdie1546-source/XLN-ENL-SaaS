<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Libraries\Database;
use App\Libraries\Config;

class EnterpriseController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');

        $sql = "SELECT ep.*, u.email, u.name
                FROM `{$prefix}enterprise_profiles` ep
                JOIN `{$prefix}users` u ON ep.user_id = u.id
                ORDER BY ep.created_at DESC";
        $enterprises = $db->fetchAll($sql);

        $this->view('admin/enterprise', [
            'currentSection' => 'enterprise',
            'enterprises' => $enterprises,
        ]);
    }

    public function toggle($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');

        $profile = $db->fetch("SELECT * FROM `{$prefix}enterprise_profiles` WHERE `id` = ?", [$id]);
        if (!$profile) {
            $_SESSION['flash']['error'] = '企业记录不存在';
            $this->redirect('/admin/enterprise');
        }

        $newStatus = ($profile['is_active'] ?? 1) ? 0 : 1;
        $db->query("UPDATE `{$prefix}enterprise_profiles` SET `is_active` = ?, `updated_at` = ? WHERE `id` = ?", [
            $newStatus, date('Y-m-d H:i:s'), $id
        ]);

        $_SESSION['flash']['success'] = $newStatus ? '企业账号已启用' : '企业账号已停用';
        $this->redirect('/admin/enterprise');
    }

    public function editExpiry($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');

        $expiresAt = $_POST['expires_at'] ?? '';
        if (empty($expiresAt)) {
            $_SESSION['flash']['error'] = '请提供到期时间';
            $this->redirect('/admin/enterprise');
        }

        $db->query(
            "UPDATE `{$prefix}enterprise_profiles` SET `expires_at` = ?, `updated_at` = ? WHERE `id` = ?",
            [$expiresAt, date('Y-m-d H:i:s'), $id]
        );

        $_SESSION['flash']['success'] = '到期时间已更新';
        $this->redirect('/admin/enterprise');
    }

    public function delete($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');

        $profile = $db->fetch("SELECT * FROM `{$prefix}enterprise_profiles` WHERE `id` = ?", [$id]);
        if (!$profile) {
            $_SESSION['flash']['error'] = '企业记录不存在';
            $this->redirect('/admin/enterprise');
        }

        // Revert user_type to individual
        $db->query("UPDATE `{$prefix}users` SET `user_type` = 'individual' WHERE `id` = ?", [$profile['user_id']]);
        // Delete enterprise profile
        $db->query("DELETE FROM `{$prefix}enterprise_profiles` WHERE `id` = ?", [$id]);

        $_SESSION['flash']['success'] = '企业已删除，用户已恢复为个人版';
        $this->redirect('/admin/enterprise');
    }
}
