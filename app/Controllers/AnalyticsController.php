<?php

namespace App\Controllers;

use LinkHub\Models\Page;
use LinkHub\Models\Statistic;

class AnalyticsController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageModel = new Page();
        $statModel = new Statistic();

        $pages = $pageModel->findByUser($user['id']);
        $pageIds = array_column($pages, 'id');

        $todayViews = 0;
        $totalViews = 0;
        $totalClicks = 0;
        $dailyStats = [];
        $deviceBreakdown = [];
        $pageRanking = [];

        if (!empty($pageIds)) {
            $placeholders = implode(',', array_fill(0, count($pageIds), '?'));

            // Today's views
            $sql = "SELECT COUNT(*) as cnt FROM `{$statModel->getPrefix()}statistics` WHERE `page_id` IN ($placeholders) AND DATE(`created_at`) = DATE('now')";
            $result = $statModel->rawQuery($sql, $pageIds);
            $todayViews = (int)($result['cnt'] ?? 0);

            // Total views
            $sql = "SELECT COUNT(*) as cnt FROM `{$statModel->getPrefix()}statistics` WHERE `page_id` IN ($placeholders)";
            $result = $statModel->rawQuery($sql, $pageIds);
            $totalViews = (int)($result['cnt'] ?? 0);

            // Total clicks (link clicks)
            $sql = "SELECT COUNT(*) as cnt FROM `{$statModel->getPrefix()}statistics` WHERE `page_id` IN ($placeholders) AND `link_id` IS NOT NULL";
            $result = $statModel->rawQuery($sql, $pageIds);
            $totalClicks = (int)($result['cnt'] ?? 0);

            // Daily stats last 7 days
            $sql = "SELECT DATE(`created_at`) as date, COUNT(*) as total, COUNT(DISTINCT `ip_address`) as unique_ips
                    FROM `{$statModel->getPrefix()}statistics`
                    WHERE `page_id` IN ($placeholders) AND `created_at` >= datetime('now', '-7 days')
                    GROUP BY DATE(`created_at`) ORDER BY `date` DESC";
            $dailyStats = $statModel->rawQueryAll($sql, $pageIds);

            // Device breakdown
            $sql = "SELECT `device_type`, COUNT(*) as total
                    FROM `{$statModel->getPrefix()}statistics`
                    WHERE `page_id` IN ($placeholders) AND `created_at` >= datetime('now', '-30 days')
                    GROUP BY `device_type` ORDER BY `total` DESC";
            $deviceBreakdown = $statModel->rawQueryAll($sql, $pageIds);
        }

        // Page ranking
        $pageRanking = $pageModel->findByUser($user['id']);
        usort($pageRanking, fn($a, $b) => $b['view_count'] <=> $a['view_count']);

        $activePages = count(array_filter($pageRanking, fn($p) => ($p['view_count'] ?? 0) > 0));

        $this->view('analytics/index', [
            'todayViews' => $todayViews,
            'totalViews' => $totalViews,
            'totalClicks' => $totalClicks,
            'activePages' => $activePages,
            'dailyStats' => $dailyStats,
            'deviceBreakdown' => $deviceBreakdown,
            'pageRanking' => $pageRanking,
        ]);
    }
}
