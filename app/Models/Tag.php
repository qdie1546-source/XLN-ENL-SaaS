<?php

namespace LinkHub\Models;

class Tag extends Model
{
    protected $table = 'theme_tags';

    public function all(): array
    {
        $sql = "SELECT * FROM `{$this->prefix}theme_tags` ORDER BY `id` DESC";
        return $this->db->fetchAll($sql);
    }

    public function bySlug($slug)
    {
        return $this->findBy('slug', $slug);
    }

    public function forTheme($themeId, $themeType = 'user')
    {
        $sql = "SELECT t.* FROM `{$this->prefix}theme_tags` t
                INNER JOIN `{$this->prefix}theme_tag_relations` r ON r.tag_id = t.id
                WHERE r.theme_id = ? AND r.theme_type = ?
                ORDER BY t.name";
        return $this->db->fetchAll($sql, [$themeId, $themeType]);
    }

    public function setThemeTags($themeId, array $tagIds, $themeType = 'user')
    {
        // Remove existing
        $this->db->query("DELETE FROM `{$this->prefix}theme_tag_relations` WHERE `theme_id` = ? AND `theme_type` = ?", [$themeId, $themeType]);
        // Insert new
        foreach ($tagIds as $tagId) {
            $this->db->query("INSERT INTO `{$this->prefix}theme_tag_relations` (`theme_id`, `tag_id`, `theme_type`) VALUES (?, ?, ?)", [$themeId, $tagId, $themeType]);
        }
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM `{$this->prefix}theme_tag_relations` WHERE `tag_id` = ?", [$id]);
        return parent::delete($id);
    }
}
