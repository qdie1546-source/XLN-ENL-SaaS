<?php

namespace App\Controllers;

use LinkHub\Models\User;
use App\Libraries\Session;

class AuthController extends Controller
{
    public function login()
    {
        if ($this->auth()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login');
    }

    public function doLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->view('auth/login', ['error' => '请输入邮箱和密码']);
            return;
        }

        $userModel = new User();
        $user = $userModel->authenticate($email, $password);

        if ($user) {
            unset($user['password']);
            Session::set('user', $user);
            $this->redirect('/dashboard');
        }

        $this->view('auth/login', ['error' => '邮箱或密码错误']);
    }

    public function register()
    {
        if ($this->auth()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/register');
    }

    public function doRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirmation'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $this->view('auth/register', ['error' => '请填写所有必填项']);
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->view('auth/register', ['error' => '两次密码输入不一致']);
            return;
        }

        if (strlen($password) < 6) {
            $this->view('auth/register', ['error' => '密码长度至少6位']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('auth/register', ['error' => '请输入有效的邮箱地址']);
            return;
        }

        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            $this->view('auth/register', ['error' => '该邮箱已被注册']);
            return;
        }

        $userId = $userModel->register($email, $password, $name);

        if ($userId) {
            $user = $userModel->find($userId);
            unset($user['password']);
            Session::set('user', $user);
            $this->redirect('/dashboard');
        }

        $this->view('auth/register', ['error' => '注册失败，请重试']);
    }

    public function logout()
    {
        Session::remove('user');
        $this->redirect('/');
    }
}
