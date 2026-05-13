<?php

namespace LinkHub\Models;

class Statistic extends Model
{
    protected $table = 'statistics';

    public function logPageView($pageId, $data = [])
    {
        return $this->create([
            'page_id' => $pageId,
            'link_id' => $data['link_id'] ?? null,
            'ip_address' => $data['ip_address'] ?? $_SERVER['REMOTE_ADDR'] ?? '',
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'device_type' => $data['device_type'] ?? $this->detectDevice(),
            'browser' => $data['browser'] ?? $_SERVER['HTTP_USER_AGENT'] ?? '',
            'os' => $data['os'] ?? $_SERVER['HTTP_USER_AGENT'] ?? '',
            'referer' => $data['referer'] ?? $_SERVER['HTTP_REFERER'] ?? null,
        ]);
    }

    public function pageStatistics($pageId, $days = 30)
    {
        $sql = "SELECT
                    DATE(`created_at`) as date,
                    COUNT(*) as total,
                    COUNT(DISTINCT `ip_address`) as unique_ips
                FROM `{$this->prefix}statistics`
                WHERE `page_id` = ?
                AND `created_at` >= datetime('now', '-' || ? || ' days')
                GROUP BY DATE(`created_at`)
                ORDER BY `date` DESC";
        return $this->db->fetchAll($sql, [$pageId, $days]);
    }

    public function linkStatistics($linkId, $days = 30)
    {
        $sql = "SELECT
                    DATE(`created_at`) as date,
                    COUNT(*) as total
                FROM `{$this->prefix}statistics`
                WHERE `link_id` = ?
                AND `created_at` >= datetime('now', '-' || ? || ' days')
                GROUP BY DATE(`created_at`)
                ORDER BY `date` DESC";
        return $this->db->fetchAll($sql, [$linkId, $days]);
    }

    public function deviceBreakdown($pageId, $days = 30)
    {
        $sql = "SELECT
                    `device_type`,
                    COUNT(*) as total
                FROM `{$this->prefix}statistics`
                WHERE `page_id` = ?
                AND `created_at` >= datetime('now', '-' || ? || ' days')
                GROUP BY `device_type`";
        return $this->db->fetchAll($sql, [$pageId, $days]);
    }
    
    public function totalClicks()
    {
        $sql = "SELECT COUNT(*) as total FROM `{$this->prefix}statistics` WHERE `link_id` IS NOT NULL";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }
    
    public function totalViews()
    {
        $sql = "SELECT COUNT(*) as total FROM `{$this->prefix}statistics` WHERE `page_id` IS NOT NULL";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    protected function detectDevice()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $userAgent)) {
            return 'tablet';
        } elseif (preg_match('/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|Opera Mini/i', $userAgent)) {
            return 'mobile';
        }
        return 'desktop';
    }
}
