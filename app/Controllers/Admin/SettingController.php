<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Libraries\Database;
use App\Libraries\Config;

class SettingController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        // Load settings from DB
        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');
        $settings = $db->fetchAll("SELECT * FROM `{$prefix}config`");

        $configMap = [];
        foreach ($settings as $row) {
            $configMap[$row['key']] = $row['value'];
        }

        $this->view('admin/settings', [
            'currentSection' => 'settings',
            'settings' => $configMap,
        ]);
    }

    public function update()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $siteName = $_POST['site_name'] ?? '';
        $siteDescription = $_POST['site_description'] ?? '';
        $allowRegistration = isset($_POST['allow_registration']) ? '1' : '0';

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');

        $pairs = [
            'site_name' => $siteName,
            'site_description' => $siteDescription,
            'allow_registration' => $allowRegistration,
        ];

        foreach ($pairs as $key => $value) {
            $existing = $db->fetch("SELECT id FROM `{$prefix}config` WHERE `key` = ?", [$key]);
            if ($existing) {
                $db->query("UPDATE `{$prefix}config` SET `value` = ?, `updated_at` = ? WHERE `key` = ?", [$value, date('Y-m-d H:i:s'), $key]);
            } else {
                $db->query("INSERT INTO `{$prefix}config` (`key`, `value`, `updated_at`) VALUES (?, ?, ?)", [$key, $value, date('Y-m-d H:i:s')]);
            }
        }

        $_SESSION['flash']['success'] = '设置已保存';
        $this->redirect('/admin/settings');
    }
}
