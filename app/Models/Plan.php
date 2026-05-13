<?php

namespace LinkHub\Models;

class Plan extends Model
{
    protected $table = 'plans';

    public function active()
    {
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
        return $this->db->fetchAll("SELECT * FROM `{$prefix}plans` WHERE `is_active` = 1 ORDER BY `price` ASC");
    }

    public function bySlug($slug)
    {
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
        return $this->db->fetch("SELECT * FROM `{$prefix}plans` WHERE `slug` = ?", [$slug]);
    }
}
