<?php

namespace App\Controllers;

use LinkHub\Models\Page;
use LinkHub\Models\Link;
use App\Libraries\Config;

class PageController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageModel = new Page();
        $pages = $pageModel->findByUser($user['id']);

        $this->view('pages/index', ['user' => $user, 'pages' => $pages]);
    }

    public function create()
    {
        $this->requireAuth();
        $this->view('pages/create');
    }

    public function store()
    {
        $this->requireAuth();
        $user = $this->auth();

        $slug = $this->getPost('slug');
        $title = $this->getPost('title', '我的链接');
        $bio = $this->getPost('bio');

        if (empty($slug)) {
            $_SESSION['flash']['error'] = '请输入页面地址';
            $this->back();
        }

        if (!preg_match('/^[a-z0-9_-]+$/', $slug)) {
            $_SESSION['flash']['error'] = '页面地址只能包含字母、数字、下划线和短横线';
            $this->back();
        }

        $pageModel = new Page();
        $existing = $pageModel->findBySlug($slug);
        if ($existing) {
            $_SESSION['flash']['error'] = '该地址已被占用';
            $this->back();
        }

        $pageModel->create([
            'user_id' => $user['id'],
            'slug' => $slug,
            'title' => $title,
            'bio' => $bio,
            'is_published' => 1,
        ]);

        $_SESSION['flash']['success'] = '页面创建成功！';
        $this->redirect('/dashboard');
    }

    public function edit($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageModel = new Page();
        $page = $pageModel->find($id);

        if (!$page || $page['user_id'] != $user['id']) {
            $_SESSION['flash']['error'] = '页面不存在';
            $this->redirect('/dashboard');
        }

        $links = $pageModel->links($id);

        $this->view('pages/edit', [
            'page' => $page,
            'links' => $links,
        ]);
    }

    public function update($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageModel = new Page();
        $page = $pageModel->find($id);

        if (!$page || $page['user_id'] != $user['id']) {
            $_SESSION['flash']['error'] = '页面不存在';
            $this->redirect('/dashboard');
        }

        $title = $this->getPost('title', $page['title']);
        $bio = $this->getPost('bio');

        $pageModel->update($id, [
            'title' => $title,
            'bio' => $bio,
        ]);

        $_SESSION['flash']['success'] = '页面更新成功！';
        $this->back();
    }

    public function delete($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageModel = new Page();
        $page = $pageModel->find($id);

        if (!$page || $page['user_id'] != $user['id']) {
            $_SESSION['flash']['error'] = '页面不存在';
            $this->redirect('/dashboard');
        }

        $pageModel->delete($id);
        $_SESSION['flash']['success'] = '页面删除成功！';
        $this->redirect('/dashboard');
    }
}
