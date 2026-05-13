<?php

namespace App\Libraries;

class Response
{
    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header('Location: ' . $url);
        exit;
    }

    public static function view($template, $data = [])
    {
        extract($data);
        $file = __DIR__ . '/../Views/' . $template . '.php';
        if (file_exists($file)) {
            require $file;
        } else {
            self::error('模板不存在: ' . $template, 404);
        }
    }

    public static function error($message, $statusCode = 500)
    {
        http_response_code($statusCode);
        echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>错误</title>
</head>
<body>
    <h1>错误</h1>
    <p>' . htmlspecialchars($message) . '</p>
</body>
</html>';
        exit;
    }
}
