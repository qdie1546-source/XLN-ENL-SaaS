<?php

namespace App\Controllers;

use LinkHub\Models\Page;
use LinkHub\Models\Link;
use LinkHub\Models\Statistic;

class DashboardController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageModel = new Page();
        $pages = $pageModel->findByUser($user['id']);

        $linkModel = new Link();
        $statModel = new Statistic();

        $totalLinks = 0;
        $totalViews = 0;
        $totalClicks = 0;

        foreach ($pages as $page) {
            $links = $pageModel->links($page['id']);
            $totalLinks += count($links);
            $totalViews += $page['view_count'];
            $totalClicks += $page['click_count'];
        }

        $this->view('dashboard/index', [
            'user' => $user,
            'pages' => $pages,
            'totalPages' => count($pages),
            'totalLinks' => $totalLinks,
            'totalViews' => $totalViews,
            'totalClicks' => $totalClicks,
        ]);
    }
}
