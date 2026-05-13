<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Libraries\Database;
use App\Libraries\Config;

class PlanController extends Controller
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
        $plans = $db->fetchAll("SELECT * FROM `{$prefix}plans` ORDER BY `created_at` DESC");

        $this->view('admin/plans', [
            'currentSection' => 'plans',
            'plans' => $plans,
        ]);
    }

    public function store()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');

        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $type = $_POST['type'] ?? 'enterprise';
        $pageLimit = intval($_POST['page_limit'] ?? 0);
        $durationDays = intval($_POST['duration_days'] ?? 365);

        if (empty($name) || empty($slug)) {
            $_SESSION['flash']['error'] = '请填写名称和标识';
            $this->redirect('/admin/plans');
        }

        $db->query(
            "INSERT INTO `{$prefix}plans` (`name`, `slug`, `description`, `price`, `type`, `page_limit`, `duration_days`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$name, $slug, $description, $price, $type, $pageLimit, $durationDays, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]
        );

        $_SESSION['flash']['success'] = '套餐已创建';
        $this->redirect('/admin/plans');
    }

    public function update($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');

        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $type = $_POST['type'] ?? 'enterprise';
        $pageLimit = intval($_POST['page_limit'] ?? 0);
        $durationDays = intval($_POST['duration_days'] ?? 365);

        $db->query(
            "UPDATE `{$prefix}plans` SET `name` = ?, `slug` = ?, `description` = ?, `price` = ?, `type` = ?, `page_limit` = ?, `duration_days` = ?, `updated_at` = ? WHERE `id` = ?",
            [$name, $slug, $description, $price, $type, $pageLimit, $durationDays, date('Y-m-d H:i:s'), $id]
        );

        $_SESSION['flash']['success'] = '套餐已更新';
        $this->redirect('/admin/plans');
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

        $plan = $db->fetch("SELECT * FROM `{$prefix}plans` WHERE `id` = ?", [$id]);
        if (!$plan) {
            $_SESSION['flash']['error'] = '套餐不存在';
            $this->redirect('/admin/plans');
        }

        $newStatus = ($plan['is_active'] ?? 1) ? 0 : 1;
        $db->query("UPDATE `{$prefix}plans` SET `is_active` = ?, `updated_at` = ? WHERE `id` = ?", [
            $newStatus, date('Y-m-d H:i:s'), $id
        ]);

        $_SESSION['flash']['success'] = $newStatus ? '套餐已上架' : '套餐已下架';
        $this->redirect('/admin/plans');
    }
}
