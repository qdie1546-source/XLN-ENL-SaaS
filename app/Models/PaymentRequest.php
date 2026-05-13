<?php

namespace LinkHub\Models;

class PaymentRequest extends Model
{
    protected $table = 'payment_requests';

    public function findByOutTradeNo(string $outTradeNo)
    {
        return $this->findBy('out_trade_no', $outTradeNo);
    }

    public function createRequest(int $userId, string $orderType, ?int $orderId, float $amount, string $title, array $metadata = [])
    {
        return $this->create([
            'user_id' => $userId,
            'order_type' => $orderType,
            'order_id' => $orderId,
            'provider' => 'epay',
            'out_trade_no' => $this->generateOutTradeNo(),
            'title' => $title,
            'amount' => $amount,
            'currency' => 'CNY',
            'status' => 'pending',
            'metadata' => json_encode($metadata, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function markCompleted(int $id, string $providerTradeNo)
    {
        return $this->update($id, [
            'status' => 'completed',
            'provider_trade_no' => $providerTradeNo,
        ]);
    }

    private function generateOutTradeNo(): string
    {
        return 'EPAY' . date('YmdHis') . random_int(1000, 9999);
    }
}
