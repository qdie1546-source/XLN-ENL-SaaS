<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Libraries\Config;
use App\Libraries\Database;
use LinkHub\Models\UserTheme;
use LinkHub\Models\User;

class ThemeMarketController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $tab = $_GET['tab'] ?? 'all';

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');

        $where = '';
        $params = [];
        switch ($tab) {
            case 'pending':
                $where = "WHERE ut.`status` = 'pending'";
                break;
            case 'approved':
                $where = "WHERE ut.`status` = 'approved' AND ut.`is_active` = 1";
                break;
            case 'rejected':
                $where = "WHERE ut.`status` = 'rejected'";
                break;
            case 'delisted':
                $where = "WHERE ut.`is_active` = 0";
                break;
            default:
                $tab = 'all';
                break;
        }

        $sql = "SELECT ut.*, u.name as author_name
                FROM `{$prefix}user_themes` ut
                JOIN `{$prefix}users` u ON ut.user_id = u.id
                {$where}
                ORDER BY ut.`created_at` DESC";
        $themes = $db->fetchAll($sql, $params);

        // Get tags for each theme
        $tagModel = new \LinkHub\Models\Tag();
        foreach ($themes as &$t) {
            $t['tags'] = $tagModel->forTheme($t['id'], 'user');
        }

        $this->view('admin/theme-market', [
            'currentSection' => 'theme-market',
            'themes' => $themes,
            'tab' => $tab,
        ]);
    }

    public function approve($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $userThemeModel = new UserTheme();
        $theme = $userThemeModel->find($id);

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/admin/theme-market');
        }

        $userThemeModel->update($id, ['status' => 'approved']);

        $_SESSION['flash']['success'] = '主题「' . htmlspecialchars($theme['name']) . '」已审核通过';
        $this->redirect('/admin/theme-market');
    }

    public function reject($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $userThemeModel = new UserTheme();
        $theme = $userThemeModel->find($id);

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/admin/theme-market');
        }

        $userThemeModel->update($id, ['status' => 'rejected']);

        $_SESSION['flash']['success'] = '主题「' . htmlspecialchars($theme['name']) . '」已拒绝';
        $this->redirect('/admin/theme-market');
    }

    public function toggle($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $userThemeModel = new UserTheme();
        $theme = $userThemeModel->find($id);

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/admin/theme-market');
        }

        $newStatus = $theme['is_active'] ? 0 : 1;
        $userThemeModel->update($id, ['is_active' => $newStatus]);

        $_SESSION['flash']['success'] = $newStatus ? '主题已上架' : '主题已下架';
        $this->redirect('/admin/theme-market');
    }

    public function edit($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $userThemeModel = new UserTheme();
        $theme = $userThemeModel->find($id);

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/admin/theme-market');
        }

        $userModel = new User();
        $author = $userModel->find($theme['user_id']);
        $theme['author_name'] = $author['name'] ?? '未知';

        $tagModel = new \LinkHub\Models\Tag();
        $theme['tags'] = $tagModel->forTheme($id, 'user');
        $allTags = $tagModel->all();

        $this->view('admin/theme-market-edit', [
            'theme' => $theme,
            'allTags' => $allTags,
            'currentSection' => 'theme-market',
        ]);
    }

    public function update($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $userThemeModel = new UserTheme();
        $theme = $userThemeModel->find($id);

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/admin/theme-market');
        }

        $name = $this->getPost('name', $theme['name']);
        $description = $this->getPost('description', $theme['description'] ?? '');
        $cssContent = $this->getPost('css_content', $theme['css_content'] ?? '');
        $price = max(0, floatval($this->getPost('price', $theme['price'] ?? 0)));
        $isActive = $this->getPost('is_active') ? 1 : 0;

        $userThemeModel->update($id, [
            'name' => $name,
            'description' => $description,
            'css_content' => $cssContent,
            'price' => $price,
            'is_active' => $isActive,
        ]);

        // Update tags if posted
        if (isset($_POST['tags']) && is_array($_POST['tags'])) {
            $tagModel = new \LinkHub\Models\Tag();
            $tagModel->setThemeTags($id, $_POST['tags'], 'user');
        }

        $_SESSION['flash']['success'] = '主题「' . htmlspecialchars($name) . '」更新成功';
        $this->redirect('/admin/theme-market');
    }

    public function content($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $userThemeModel = new UserTheme();
        $theme = $userThemeModel->find($id);

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/admin/theme-market');
        }

        $userModel = new User();
        $author = $userModel->find($theme['user_id']);
        $theme['author_name'] = $author['name'] ?? '未知';

        $this->view('admin/theme-market-content', [
            'theme' => $theme,
            'currentSection' => 'theme-market',
        ]);
    }
}
