<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use LinkHub\Models\User;
use LinkHub\Models\Page;
use LinkHub\Models\Statistic;

class StatsController extends Controller
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
        $pageModel = new Page();
        $statModel = new Statistic();

        $totalUsers = $userModel->count();
        $totalPages = $pageModel->count();
        $totalViews = $statModel->totalViews();
        $totalClicks = $statModel->totalClicks();

        // Recent users
        $recentUsers = $userModel->recent(5);

        // Recent pages
        $recentPages = $pageModel->recent(5);

        // Enterprise users count
        $sql = "SELECT COUNT(*) as cnt FROM `{$statModel->getPrefix()}users` WHERE `user_type` = 'enterprise'";
        $enterpriseCount = (int)($statModel->rawQuery($sql)['cnt'] ?? 0);

        $this->view('admin/stats', [
            'currentSection' => 'stats',
            'totalUsers' => $totalUsers,
            'totalPages' => $totalPages,
            'totalViews' => $totalViews,
            'totalClicks' => $totalClicks,
            'enterpriseCount' => $enterpriseCount,
            'recentUsers' => $recentUsers,
            'recentPages' => $recentPages,
        ]);
    }
}
