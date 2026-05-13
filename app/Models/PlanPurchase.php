<?php

namespace LinkHub\Models;

class PlanPurchase extends Model
{
    protected $table = 'plan_purchases';

    public function byUser($userId)
    {
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
        return $this->db->fetchAll(
            "SELECT pp.*, p.name as plan_name, p.type, p.page_limit
             FROM `{$prefix}plan_purchases` pp
             JOIN `{$prefix}plans` p ON pp.plan_id = p.id
             WHERE pp.user_id = ?
             ORDER BY pp.created_at DESC",
            [$userId]
        );
    }
}
