<?php

namespace App\Libraries;

class Config
{
    private static $config = [];
    private static $initialized = false;

    public static function load($configDir = null)
    {
        if (self::$initialized) {
            return;
        }

        if (is_null($configDir)) {
            $configDir = __DIR__ . '/../../config/';
        }

        $files = glob($configDir . '*.php');
        foreach ($files as $file) {
            $name = basename($file, '.php');
            self::$config[$name] = require $file;
        }

        self::$initialized = true;
    }

    public static function get($key, $default = null)
    {
        if (!self::$initialized) {
            self::load();
        }

        $parts = explode('.', $key);
        $value = self::$config;

        foreach ($parts as $part) {
            if (!isset($value[$part])) {
                return $default;
            }
            $value = $value[$part];
        }

        return $value;
    }

    public static function set($key, $value)
    {
        $parts = explode('.', $key);
        $config = &self::$config;

        foreach ($parts as $i => $part) {
            if ($i === count($parts) - 1) {
                $config[$part] = $value;
            } else {
                if (!isset($config[$part])) {
                    $config[$part] = [];
                }
                $config = &$config[$part];
            }
        }
    }
}
