<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use LinkHub\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();
        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $tagModel = new Tag();
        $tags = $tagModel->all();

        $this->view('admin/tags', [
            'tags' => $tags,
            'currentSection' => 'tags',
        ]);
    }

    public function store()
    {
        $this->requireAuth();
        $user = $this->auth();
        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $_SESSION['flash']['error'] = '请输入标签名称';
            $this->redirect('/admin/tags');
        }

        $slug = strtolower(preg_replace('/[^a-zA-Z0-9\x{4e00}-\x{9fff}]+/u', '-', $name));
        $slug = trim($slug, '-') ?: 'tag-' . time();

        $tagModel = new Tag();
        $existing = $tagModel->bySlug($slug);
        if ($existing) {
            $_SESSION['flash']['error'] = '标签已存在';
            $this->redirect('/admin/tags');
        }

        $tagModel->create(['name' => $name, 'slug' => $slug, 'created_at' => date('Y-m-d H:i:s')]);
        $_SESSION['flash']['success'] = '标签已添加';
        $this->redirect('/admin/tags');
    }

    public function update($id)
    {
        $this->requireAuth();
        $user = $this->auth();
        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $_SESSION['flash']['error'] = '请输入标签名称';
            $this->redirect('/admin/tags');
        }

        $tagModel = new Tag();
        $tag = $tagModel->find($id);
        if (!$tag) {
            $_SESSION['flash']['error'] = '标签不存在';
            $this->redirect('/admin/tags');
        }

        $slug = strtolower(preg_replace('/[^a-zA-Z0-9\x{4e00}-\x{9fff}]+/u', '-', $name));
        $slug = trim($slug, '-') ?: $tag['slug'];

        $tagModel->update($id, ['name' => $name, 'slug' => $slug]);
        $_SESSION['flash']['success'] = '标签已更新';
        $this->redirect('/admin/tags');
    }

    public function delete($id)
    {
        $this->requireAuth();
        $user = $this->auth();
        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $tagModel = new Tag();
        $tagModel->delete($id);
        $_SESSION['flash']['success'] = '标签已删除';
        $this->redirect('/admin/tags');
    }
}
