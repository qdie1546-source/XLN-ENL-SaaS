<?php
/**
 * Config Model - Platform Configuration
 */

namespace App\Models;

class Config
{
    private static ?array $cache = null;
    
    /**
     * Get all configurations
     */
    public static function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }
        
        $db = Database::getInstance();
        $stmt = $db->query("SELECT `key`, `value`, `type`, `group`, `label`, `description` FROM `config`");
        $configs = $stmt->fetchAll();
        
        $result = [];
        foreach ($configs as $config) {
            $result[$config['key']] = [
                'value' => self::parseValue($config['value'], $config['type']),
                'type' => $config['type'],
                'group' => $config['group'],
                'label' => $config['label'],
                'description' => $config['description']
            ];
        }
        
        self::$cache = $result;
        return $result;
    }
    
    /**
     * Get configuration by key
     */
    public static function get(string $key, $default = null)
    {
        $configs = self::all();
        return $configs[$key]['value'] ?? $default;
    }
    
    /**
     * Set configuration value
     */
    public static function set(string $key, $value): bool
    {
        $db = Database::getInstance();
        
        $configs = self::all();
        if (!isset($configs[$key])) {
            return false;
        }
        
        $type = $configs[$key]['type'];
        $dbValue = self::serializeValue($value, $type);
        
        $stmt = $db->prepare("UPDATE `config` SET `value` = ? WHERE `key` = ?");
        $result = $stmt->execute([$dbValue, $key]);
        
        if ($result) {
            self::$cache = null;
        }
        
        return $result;
    }
    
    /**
     * Update multiple configurations
     */
    public static function update(array $data): bool
    {
        $db = Database::getInstance();
        $configs = self::all();
        
        foreach ($data as $key => $value) {
            if (!isset($configs[$key])) {
                continue;
            }
            
            $type = $configs[$key]['type'];
            $dbValue = self::serializeValue($value, $type);
            
            $stmt = $db->prepare("UPDATE `config` SET `value` = ? WHERE `key` = ?");
            $stmt->execute([$dbValue, $key]);
        }
        
        self::$cache = null;
        return true;
    }
    
    /**
     * Get configurations by group
     */
    public static function getByGroup(string $group): array
    {
        $configs = self::all();
        $result = [];
        
        foreach ($configs as $key => $config) {
            if ($config['group'] === $group) {
                $result[$key] = $config;
            }
        }
        
        return $result;
    }
    
    /**
     * Parse value based on type
     */
    private static function parseValue(string $value, string $type)
    {
        switch ($type) {
            case 'number':
                return is_numeric($value) ? (strpos($value, '.') !== false ? (float)$value : (int)$value) : $value;
            case 'boolean':
                return in_array(strtolower($value), ['1', 'true', 'yes']);
            case 'json':
                return json_decode($value, true) ?? $value;
            default:
                return $value;
        }
    }
    
    /**
     * Serialize value for storage
     */
    private static function serializeValue($value, string $type): string
    {
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
            case 'json':
                return is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE);
            default:
                return (string)$value;
        }
    }
    
    /**
     * Clear cache
     */
    public static function clearCache(): void
    {
        self::$cache = null;
    }
}
