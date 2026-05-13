<?php

namespace LinkHub\Models;

class Wallet extends Model
{
    protected $table = 'wallets';

    public function getByUser($userId)
    {
        $wallet = $this->findBy('user_id', $userId);
        if (!$wallet) {
            $this->create(['user_id' => $userId, 'balance' => 0.00]);
            return $this->findBy('user_id', $userId);
        }
        return $wallet;
    }

    public function addBalance($userId, $amount)
    {
        $wallet = $this->getByUser($userId);
        $newBalance = floatval($wallet['balance']) + $amount;
        $this->update($wallet['id'], ['balance' => $newBalance, 'updated_at' => date('Y-m-d H:i:s')]);
        return $newBalance;
    }

    public function deductBalance($userId, $amount)
    {
        $wallet = $this->getByUser($userId);
        if (floatval($wallet['balance']) < $amount) {
            return false;
        }
        $newBalance = floatval($wallet['balance']) - $amount;
        $this->update($wallet['id'], ['balance' => $newBalance, 'updated_at' => date('Y-m-d H:i:s')]);
        return $newBalance;
    }
}
