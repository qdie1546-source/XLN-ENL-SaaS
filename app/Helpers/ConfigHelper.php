<?php
/**
 * Config Helper - Simple config loader for templates
 */

class ConfigHelper
{
    private static ?array $configs = null;
    
    /**
     * Load configurations from database
     */
    public static function load(): array
    {
        if (self::$configs !== null) {
            return self::$configs;
        }
        
        self::$configs = [
            'site_name' => 'LinkHub',
            'site_logo' => '',
            'site_description' => '社交链接聚合平台',
            'ai_enabled' => true,
            'ai_model' => 'gpt-3.5-turbo',
            'maintenance_mode' => false,
            'allow_registration' => true,
        ];
        
        try {
            $dbFile = dirname(__DIR__, 2) . '/database/database.sqlite';
            if (file_exists($dbFile)) {
                $db = new \PDO('sqlite:' . $dbFile);
                $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                
                $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='lh_config'");
                if ($stmt->fetch()) {
                    $result = $db->query("SELECT key, value FROM lh_config");
                    while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
                        self::$configs[$row['key']] = self::parseValue($row['value']);
                    }
                }
            }
        } catch (\Exception $e) {
            // Use default values if database not available
        }
        
        return self::$configs;
    }
    
    /**
     * Get config value
     */
    public static function get(string $key, $default = null)
    {
        $configs = self::load();
        return $configs[$key] ?? $default;
    }
    
    /**
     * Get site name
     */
    public static function siteName(): string
    {
        return self::get('site_name', 'LinkHub');
    }
    
    /**
     * Parse value
     */
    private static function parseValue(string $value)
    {
        if (in_array(strtolower($value), ['1', 'true', 'yes'])) {
            return true;
        }
        if (in_array(strtolower($value), ['0', 'false', 'no'])) {
            return false;
        }
        return $value;
    }
}
