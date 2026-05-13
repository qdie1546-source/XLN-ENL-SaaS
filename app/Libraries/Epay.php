<?php

namespace App\Libraries;

class Epay
{
    public static function buildSignature(array $params, string $key): string
    {
        $filtered = [];
        foreach ($params as $name => $value) {
            if ($name === 'sign' || $name === 'sign_type' || $name === 'key' || $value === '' || $value === null) {
                continue;
            }
            $filtered[$name] = is_scalar($value) ? stripslashes((string)$value) : $value;
        }

        ksort($filtered);

        $parts = [];
        foreach ($filtered as $name => $value) {
            $parts[] = $name . '=' . $value;
        }

        return strtolower(md5(implode('&', $parts) . $key));
    }

    public static function buildPaymentUrl(array $params, string $apiUrl): string
    {
        $params['sign'] = self::buildSignature($params, $params['key'] ?? '');
        $params['sign_type'] = 'MD5';

        unset($params['key']);

        $apiUrl = rtrim($apiUrl, '/');
        return $apiUrl . '/submit.php?' . http_build_query($params);
    }

    public static function verifySignature(array $params, string $key): bool
    {
        $provided = strtolower($params['sign'] ?? '');
        return $provided !== '' && $provided === self::buildSignature($params, $key);
    }

    public static function isSuccessStatus(string $status): bool
    {
        return stripos($status, 'success') !== false;
    }
}
