<?php

namespace LinkHub\Models;

class Link extends Model
{
    protected $table = 'links';

    public function findByPage($pageId)
    {
        $sql = "SELECT * FROM `{$this->prefix}links` WHERE `page_id` = ? ORDER BY `position` ASC, `id` ASC";
        return $this->db->fetchAll($sql, [$pageId]);
    }

    public function incrementClickCount($linkId)
    {
        $sql = "UPDATE `{$this->prefix}links` SET `click_count` = `click_count` + 1 WHERE `id` = ?";
        $this->db->query($sql, [$linkId]);
    }

    public function reorder($pageId, $order)
    {
        foreach ($order as $position => $linkId) {
            $sql = "UPDATE `{$this->prefix}links` SET `position` = ? WHERE `id` = ? AND `page_id` = ?";
            $this->db->query($sql, [$position, $linkId, $pageId]);
        }
    }
}
