<?php

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

if (file_exists(BASE_PATH . '/.env')) {
    $env = parse_ini_file(BASE_PATH . '/.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}

require BASE_PATH . '/vendor/autoload.php';

if (class_exists('LinkHub\Libraries\Config')) {
    LinkHub\Libraries\Config::load();
} else {
    App\Libraries\Config::load();
}

$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = parse_url($requestUri, PHP_URL_PATH);

$basePath = dirname($_SERVER['SCRIPT_NAME']);
if ($basePath !== '/') {
    $requestUri = substr($requestUri, strlen($basePath));
}

$requestUri = '/' . ltrim($requestUri, '/');

$routes = require BASE_PATH . '/routes/web.php';

$found = false;

foreach ($routes as $route) {
    list($method, $pattern, $handler) = $route;
    
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        continue;
    }
    
    $pattern = str_replace('/', '\/', $pattern);
    $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $pattern);
    $pattern = '/^' . $pattern . '$/';
    
    if (preg_match($pattern, $requestUri, $matches)) {
        array_shift($matches);
        
        if (is_callable($handler)) {
            call_user_func_array($handler, $matches);
        } else {
            if (strpos($handler, '@') !== false) {
                list($controller, $action) = explode('@', $handler);
            } else {
                $parts = explode('/', $handler);
                $action = array_pop($parts);
                $controller = implode('\\', $parts);
                if (!str_ends_with($controller, 'Controller')) {
                    $controller .= 'Controller';
                }
            }

            $nsController = str_replace('/', '\\', $controller);
            $controllerClass = 'LinkHub\\Controllers\\' . $nsController;
            if (!class_exists($controllerClass)) {
                $controllerClass = 'App\\Controllers\\' . $nsController;
            }
            $instance = new $controllerClass();
            call_user_func_array([$instance, $action], $matches);
        }
        
        $found = true;
        break;
    }
}

if (!$found) {
    http_response_code(404);
    echo '<h1>404 Not Found</h1>';
}
