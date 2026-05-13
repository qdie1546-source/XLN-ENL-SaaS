<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Libraries\Config as LibConfig;
use App\Libraries\Database;

class ConfigController extends Controller
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
        $prefix = LibConfig::get('database.prefix', 'lh_');
        $rows = $db->fetchAll("SELECT * FROM `{$prefix}config` ORDER BY `id`");
        $configs = [];
        foreach ($rows as $row) {
            $configs[$row['key']] = $row['value'];
        }

        $this->view('admin/config/index', [
            'title' => '站点设置',
            'configs' => $configs,
            'currentSection' => 'config',
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

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/config');
        }

        $db = Database::getInstance();
        $prefix = LibConfig::get('database.prefix', 'lh_');

        foreach ($_POST as $key => $value) {
            if ($key === '_token') continue;
            $db->query(
                "INSERT INTO `{$prefix}config` (`key`, `value`) VALUES (?, ?) ON CONFLICT(`key`) DO UPDATE SET `value` = ?",
                [$key, $value, $value]
            );
        }

        $_SESSION['flash']['success'] = '设置已保存';
        $this->redirect('/admin/config');
    }
}
