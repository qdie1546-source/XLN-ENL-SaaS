<?php

namespace App\Controllers;

class ErrorController extends Controller
{
    /**
     * 404 页面
     */
    public function notFound()
    {
        http_response_code(404);
        $this->view('errors/404');
    }

    /**
     * 500 服务器错误页面
     */
    public function serverError()
    {
        http_response_code(500);
        $this->view('errors/500');
    }

    /**
     * 403 禁止访问
     */
    public function forbidden()
    {
        http_response_code(403);
        $this->view('errors/403');
    }
}
