<?php

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit;
}

function old($key, $default = '')
{
    return \App\Libraries\Session::get('old_' . $key, $default);
}

function redirect($url)
{
    \App\Libraries\Response::redirect($url);
}

function back()
{
    redirect($_SERVER['HTTP_REFERER'] ?? url('/'));
}

function view($template, $data = [])
{
    extract($data);
    $file = BASE_PATH . '/app/Views/' . $template . '.php';
    if (file_exists($file)) {
        require $file;
    } else {
        http_response_code(500);
        echo '模板不存在: ' . htmlspecialchars($template);
        exit;
    }
}
