<?php

namespace LinkHub\Models;

class Theme extends Model
{
    protected $table = 'themes';

    public function findBySlug($slug)
    {
        return $this->findBy('slug', $slug);
    }

    public function activeThemes()
    {
        $sql = "SELECT * FROM `{$this->prefix}themes` WHERE `is_active` = 1";
        return $this->db->fetchAll($sql);
    }

    public function freeThemes()
    {
        $sql = "SELECT * FROM `{$this->prefix}themes` WHERE `is_free` = 1 AND `is_active` = 1";
        return $this->db->fetchAll($sql);
    }
}
