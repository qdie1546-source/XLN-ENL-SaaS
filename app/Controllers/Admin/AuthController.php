<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use LinkHub\Models\User;
use App\Libraries\Session;

class AuthController extends Controller
{
    public function login()
    {
        if (self::isAdminLoggedIn()) {
            $this->redirect('/admin');
        }
        $this->view('admin/login');
    }

    public function doLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/login');
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->view('admin/login', ['error' => '请输入用户名和密码']);
            return;
        }

        $userModel = new User();
        $admin = $userModel->authenticate($username, $password);

        if ($admin && !empty($admin['is_admin'])) {
            unset($admin['password']);
            Session::set('user', $admin);
            Session::set('admin_logged_in', true);
            $this->redirect('/admin');
        }

        $this->view('admin/login', ['error' => '用户名或密码错误']);
    }

    public function logout()
    {
        Session::remove('user');
        Session::remove('admin_logged_in');
        $this->redirect('/admin/login');
    }

    public static function isAdminLoggedIn()
    {
        return Session::has('admin_logged_in');
    }
}
