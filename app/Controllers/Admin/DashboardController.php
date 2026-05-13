<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Libraries\Database;
use App\Libraries\Config;

class DashboardController extends Controller
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

        $totalUsers = $db->fetch("SELECT COUNT(*) as count FROM `{$prefix}users`")['count'] ?? 0;
        $totalPages = $db->fetch("SELECT COUNT(*) as count FROM `{$prefix}pages`")['count'] ?? 0;
        $totalLinks = $db->fetch("SELECT COUNT(*) as count FROM `{$prefix}links`")['count'] ?? 0;
        $totalViews = $db->fetch("SELECT COUNT(*) as count FROM `{$prefix}statistics`")['count'] ?? 0;
        $totalClicks = $db->fetch("SELECT COUNT(*) as count FROM `{$prefix}statistics` WHERE `link_id` IS NOT NULL")['count'] ?? 0;
        $enterpriseCount = $db->fetch("SELECT COUNT(*) as cnt FROM `{$prefix}users` WHERE `user_type` = 'enterprise'")['cnt'] ?? 0;
        $recentUsers = $db->fetchAll("SELECT * FROM `{$prefix}users` ORDER BY `created_at` DESC LIMIT 5");
        $recentPages = $db->fetchAll("SELECT * FROM `{$prefix}pages` ORDER BY `created_at` DESC LIMIT 5");

        $this->view('admin/dashboard', [
            'title' => '仪表盘',
            'currentSection' => 'dashboard',
            'totalUsers' => $totalUsers,
            'totalPages' => $totalPages,
            'totalLinks' => $totalLinks,
            'totalViews' => $totalViews,
            'totalClicks' => $totalClicks,
            'enterpriseCount' => $enterpriseCount,
            'recentUsers' => $recentUsers,
            'recentPages' => $recentPages,
        ]);
    }
}
