<?php
/**
 * API Config Controller - Configuration API
 */

namespace App\Controllers\Api;

class ConfigController
{
    /**
     * Get all configurations
     */
    public static function index(): void
    {
        header('Content-Type: application/json');
        
        $configs = \App\Helpers\ConfigHelper::load();
        
        echo json_encode($configs);
    }
    
    /**
     * Get single configuration
     */
    public static function show(string $key): void
    {
        header('Content-Type: application/json');
        
        $value = \App\Helpers\ConfigHelper::get($key);
        
        echo json_encode(['key' => $key, 'value' => $value]);
    }
}
