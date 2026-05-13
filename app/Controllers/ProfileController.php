<?php

namespace App\Controllers;

use LinkHub\Models\User;
use App\Libraries\Session;

class ProfileController extends Controller
{
    public function settings()
    {
        $this->requireAuth();
        $user = $this->auth();

        $userModel = new User();
        $currentUser = $userModel->find($user['id']);

        $this->view('profile/settings', [
            'user' => $currentUser,
            'section' => 'profile',
        ]);
    }

    public function update()
    {
        $this->requireAuth();
        $user = $this->auth();

        $name = $this->getPost('name', '');
        $phone = $this->getPost('phone', '');
        $bio = $this->getPost('bio', '');

        $userModel = new User();
        $userModel->update($user['id'], [
            'name' => $name,
            'phone' => $phone ?: null,
            'bio' => $bio ?: null,
        ]);

        Session::set('user', $userModel->find($user['id']));

        $_SESSION['flash']['success'] = '个人资料已更新';
        $this->redirect('/profile/settings');
    }

    public function password()
    {
        $this->requireAuth();
        $user = $this->auth();

        $currentPassword = $this->getPost('current_password', '');
        $newPassword = $this->getPost('new_password', '');
        $confirmPassword = $this->getPost('confirm_password', '');

        if (empty($currentPassword) || empty($newPassword)) {
            $_SESSION['flash']['error'] = '请填写所有密码字段';
            $this->redirect('/profile/settings');
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash']['error'] = '两次新密码输入不一致';
            $this->redirect('/profile/settings');
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['flash']['error'] = '新密码长度至少6位';
            $this->redirect('/profile/settings');
        }

        $userModel = new User();
        $currentUser = $userModel->find($user['id']);

        if (!password_verify($currentPassword, $currentUser['password'])) {
            $_SESSION['flash']['error'] = '当前密码不正确';
            $this->redirect('/profile/settings');
        }

        $userModel->update($user['id'], [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        $_SESSION['flash']['success'] = '密码已修改';
        $this->redirect('/profile/settings');
    }

    public function avatar()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (empty($_FILES['avatar'])) {
            $_SESSION['flash']['error'] = '请选择头像文件';
            $this->redirect('/profile/settings');
        }

        $file = $_FILES['avatar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            $_SESSION['flash']['error'] = '不支持的文件类型，请上传 JPG/PNG/GIF/WebP';
            $this->redirect('/profile/settings');
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            $_SESSION['flash']['error'] = '文件大小不能超过 2MB';
            $this->redirect('/profile/settings');
        }

        $storageDir = BASE_PATH . '/storage/avatars';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $filename = $user['id'] . '_' . time() . '.' . $ext;
        $dest = $storageDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $_SESSION['flash']['error'] = '头像上传失败';
            $this->redirect('/profile/settings');
        }

        $avatarUrl = '/storage/avatars/' . $filename;

        $userModel = new User();
        $userModel->update($user['id'], ['avatar_url' => $avatarUrl]);

        Session::set('user', $userModel->find($user['id']));

        $_SESSION['flash']['success'] = '头像已更新';
        $this->redirect('/profile/settings');
    }

    public function switchAccount()
    {
        $this->view('profile/switch-account', [
            'section' => 'profile',
        ]);
    }

    public function doSwitch($id)
    {
        $this->requireAuth();
        $currentUser = $this->auth();

        $userModel = new User();
        $targetUser = $userModel->find($id);

        if (!$targetUser) {
            $_SESSION['flash']['error'] = '账号不存在';
            $this->redirect('/profile/switch');
        }

        Session::remove('user');
        unset($targetUser['password']);
        Session::set('user', $targetUser);

        $_SESSION['flash']['success'] = '已切换到 ' . htmlspecialchars($targetUser['name']);
        $this->redirect('/dashboard');
    }

    public function toggleMode()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (($user['user_type'] ?? '') !== 'enterprise') {
            $this->redirect('/dashboard');
        }

        $currentMode = $_SESSION['view_mode'] ?? 'enterprise';
        $_SESSION['view_mode'] = $currentMode === 'enterprise' ? 'individual' : 'enterprise';

        $this->redirect('/dashboard');
    }
}
