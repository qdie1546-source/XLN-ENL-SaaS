<?php
/**
 * Routes - Admin Configuration Routes
 */

namespace App\Routes;

class AdminConfigRoutes
{
    public static function register(): void
    {
        // Config routes
        Router::get('/admin/config', [Controllers\Admin\ConfigController::class, 'index']);
        Router::post('/admin/config/update', [Controllers\Admin\ConfigController::class, 'update']);
    }
}
