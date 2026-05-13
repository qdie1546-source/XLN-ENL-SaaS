<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use LinkHub\Models\Page;

class PageController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $pageModel = new Page();
        $pages = $pageModel->all();

        // Enrich with user info and link count
        $db = \App\Libraries\Database::getInstance();
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
        foreach ($pages as &$p) {
            $owner = $db->fetch("SELECT name, email FROM `{$prefix}users` WHERE id = ?", [$p['user_id']]);
            $p['owner_name'] = $owner['name'] ?? $owner['email'] ?? '未知';
            $linkCount = $db->fetch("SELECT COUNT(*) as cnt FROM `{$prefix}links` WHERE page_id = ?", [$p['id']]);
            $p['link_count'] = $linkCount['cnt'] ?? 0;
        }

        $this->view('admin/pages', [
            'pages' => $pages,
            'currentSection' => 'pages'
        ]);
    }

    public function toggle($id)
    {
        $this->requireAuth();
        $user = $this->auth();
        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $pageModel = new Page();
        $page = $pageModel->find($id);
        if (!$page) {
            $_SESSION['flash']['error'] = '页面不存在';
            $this->redirect('/admin/pages');
        }

        $pageModel->update($id, ['is_active' => $page['is_active'] ? 0 : 1]);
        $_SESSION['flash']['success'] = '页面状态已更新';
        $this->redirect('/admin/pages');
    }

    public function delete($id)
    {
        $this->requireAuth();
        $user = $this->auth();
        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $pageModel = new Page();
        $page = $pageModel->find($id);
        if (!$page) {
            $_SESSION['flash']['error'] = '页面不存在';
            $this->redirect('/admin/pages');
        }

        // Delete links first
        $db = \App\Libraries\Database::getInstance();
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
        $db->query("DELETE FROM `{$prefix}links` WHERE page_id = ?", [$id]);

        $pageModel->delete($id);
        $_SESSION['flash']['success'] = '页面「' . htmlspecialchars($page['title']) . '」已删除';
        $this->redirect('/admin/pages');
    }
}
