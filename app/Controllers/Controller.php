<?php

namespace App\Controllers;

use App\Libraries\Session;
use App\Libraries\Response;

class Controller
{
    protected function view($template, $data = [])
    {
        extract($data);
        $file = BASE_PATH . '/app/Views/' . $template . '.php';
        if (file_exists($file)) {
            require $file;
        } else {
            Response::error('模板不存在: ' . $template, 404);
        }
    }

    protected function redirect($url)
    {
        Response::redirect($url);
    }

    protected function back()
    {
        $this->redirect($_SERVER['HTTP_REFERER'] ?? url('/'));
    }

    protected function json($data, $status = 200)
    {
        Response::json($data, $status);
    }

    protected function auth()
    {
        return Session::get('user');
    }

    protected function requireAuth()
    {
        if (!Session::has('user')) {
            $this->redirect('/login');
        }
    }

    protected function getParam($key, $default = null)
    {
        return $_GET[$key] ?? $_POST[$key] ?? $default;
    }

    protected function getPost($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }
}
