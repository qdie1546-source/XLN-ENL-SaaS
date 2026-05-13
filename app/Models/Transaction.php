<?php

namespace LinkHub\Models;

class Transaction extends Model
{
    protected $table = 'transactions';

    public function recordTransaction($userId, $type, $amount, $relatedId = null, $description = '')
    {
        return $this->create([
            'user_id' => $userId,
            'type' => $type,
            'amount' => $amount,
            'related_id' => $relatedId,
            'description' => $description,
        ]);
    }

    public function byUser($userId, $limit = 50)
    {
        $sql = "SELECT * FROM `{$this->prefix}transactions` WHERE `user_id` = ? ORDER BY `created_at` DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$userId, $limit]);
    }
}
