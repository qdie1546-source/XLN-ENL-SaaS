<?php

namespace LinkHub\Models;

class ThemePurchase extends Model
{
    protected $table = 'theme_purchases';

    public function hasPurchased($userId, $themeId)
    {
        $sql = "SELECT COUNT(*) as cnt FROM `{$this->prefix}theme_purchases` WHERE `buyer_id` = ? AND `theme_id` = ?";
        $result = $this->db->fetch($sql, [$userId, $themeId]);
        return ($result['cnt'] ?? 0) > 0;
    }

    public function byBuyer($userId)
    {
        $sql = "SELECT * FROM `{$this->prefix}theme_purchases` WHERE `buyer_id` = ? ORDER BY `created_at` DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }
}
