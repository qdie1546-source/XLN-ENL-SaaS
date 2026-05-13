<?php

namespace LinkHub\Models;

class UserTheme extends Model
{
    protected $table = 'user_themes';

    public function findBySlug($slug)
    {
        return $this->findBy('slug', $slug);
    }

    public function findByUser($userId)
    {
        $sql = "SELECT * FROM `{$this->prefix}user_themes` WHERE `user_id` = ? ORDER BY `created_at` DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function approved()
    {
        $sql = "SELECT * FROM `{$this->prefix}user_themes` WHERE `status` = 'approved' AND `is_active` = 1 ORDER BY `created_at` DESC";
        return $this->db->fetchAll($sql);
    }

    public function pending()
    {
        $sql = "SELECT * FROM `{$this->prefix}user_themes` WHERE `status` = 'pending' ORDER BY `created_at` DESC";
        return $this->db->fetchAll($sql);
    }

    public function purchasedByUser($userId)
    {
        $sql = "SELECT ut.*, tp.created_at as purchased_at
                FROM `{$this->prefix}theme_purchases` tp
                JOIN `{$this->prefix}user_themes` ut ON tp.theme_id = ut.id
                WHERE tp.buyer_id = ?
                ORDER BY tp.created_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function marketThemes()
    {
        $sql = "SELECT ut.*, u.name as author_name
                FROM `{$this->prefix}user_themes` ut
                JOIN `{$this->prefix}users` u ON ut.user_id = u.id
                WHERE ut.status = 'approved' AND ut.is_active = 1
                ORDER BY ut.created_at DESC";
        return $this->db->fetchAll($sql);
    }
}
