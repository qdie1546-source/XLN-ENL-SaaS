<?php

if (php_sapi_name() === 'cli-server') {
    $uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($uri, PHP_URL_PATH);

    $publicPath = __DIR__ . $path;
    if ($path !== '/' && file_exists($publicPath) && !is_dir($publicPath)) {
        return false;
    }

    if (str_starts_with($path, '/templates/')) {
        $templatePath = dirname(__DIR__) . $path;
        if (file_exists($templatePath) && !is_dir($templatePath)) {
            $ext = pathinfo($templatePath, PATHINFO_EXTENSION);
            $mimeTypes = [
                'css' => 'text/css',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'svg' => 'image/svg+xml',
                'html' => 'text/html',
            ];
            if (isset($mimeTypes[$ext])) {
                header('Content-Type: ' . $mimeTypes[$ext]);
            }
            readfile($templatePath);
            return true;
        }
    }
}

require __DIR__ . '/index.php';
