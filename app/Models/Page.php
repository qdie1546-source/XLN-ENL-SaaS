<?php

namespace LinkHub\Models;

class Page extends Model
{
    protected $table = 'pages';

    public function findBySlug($slug)
    {
        return $this->findBy('slug', $slug);
    }

    public function findByUser($userId)
    {
        $sql = "SELECT * FROM `{$this->prefix}pages` WHERE `user_id` = ?";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function links($pageId)
    {
        $sql = "SELECT * FROM `{$this->prefix}links` WHERE `page_id` = ? ORDER BY `position` ASC, `id` ASC";
        return $this->db->fetchAll($sql, [$pageId]);
    }

    public function incrementViewCount($pageId)
    {
        $sql = "UPDATE `{$this->prefix}pages` SET `view_count` = `view_count` + 1 WHERE `id` = ?";
        $this->db->query($sql, [$pageId]);
    }

    public function incrementClickCount($pageId)
    {
        $sql = "UPDATE `{$this->prefix}pages` SET `click_count` = `click_count` + 1 WHERE `id` = ?";
        $this->db->query($sql, [$pageId]);
    }
    
    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM `{$this->prefix}pages`";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    public function recent($limit = 10)
    {
        $sql = "SELECT * FROM `{$this->prefix}pages` ORDER BY `created_at` DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
}
