<?php

namespace App\Controllers;

use LinkHub\Models\Link;
use LinkHub\Models\Page;

class LinkController extends Controller
{
    public function index($pageId)
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageModel = new Page();
        $page = $pageModel->find($pageId);

        if (!$page || $page['user_id'] != $user['id']) {
            $_SESSION['flash']['error'] = '页面不存在';
            $this->redirect('/dashboard');
        }

        $linkModel = new Link();
        $links = $linkModel->findByPage($pageId);

        $this->view('links/index', ['page' => $page, 'links' => $links]);
    }

    public function store()
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageId = $this->getPost('page_id');
        $pageModel = new Page();
        $page = $pageModel->find($pageId);

        if (!$page || $page['user_id'] != $user['id']) {
            $_SESSION['flash']['error'] = '页面不存在';
            $this->redirect('/dashboard');
        }

        $title = $this->getPost('title', '');
        $url = $this->getPost('url', '');
        $linkType = $this->getPost('link_type', 'link');

        // Validation by type
        if ($linkType === 'divider') {
            $title = '---';
            $url = '';
        } elseif (in_array($linkType, ['html', 'text', 'headline'])) {
            if (empty($title) && empty($url)) {
                $_SESSION['flash']['error'] = '请填写内容';
                $this->back();
            }
            if ($linkType === 'html') {
                $title = 'HTML';
            }
        } else {
            if (empty($title)) {
                $_SESSION['flash']['error'] = '请填写标题';
                $this->back();
            }
            if ($linkType === 'link' && empty($url)) {
                $_SESSION['flash']['error'] = '请填写链接地址';
                $this->back();
            }
        }

        $linkModel = new Link();
        $linkModel->create([
            'page_id' => $pageId,
            'title' => $title,
            'url' => $url,
            'link_type' => $linkType,
            'is_active' => 1,
        ]);

        $_SESSION['flash']['success'] = '添加成功！';
        $this->back();
    }

    public function update($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        $linkModel = new Link();
        $link = $linkModel->find($id);

        if (!$link) {
            $_SESSION['flash']['error'] = '链接不存在';
            $this->redirect('/dashboard');
        }

        $pageModel = new Page();
        $page = $pageModel->find($link['page_id']);

        if (!$page || $page['user_id'] != $user['id']) {
            $_SESSION['flash']['error'] = '无权操作';
            $this->redirect('/dashboard');
        }

        $title = $this->getPost('title', $link['title']);
        $url = $this->getPost('url', $link['url']);
        $linkType = $this->getPost('link_type', $link['link_type'] ?? 'link');

        $linkModel->update($id, [
            'title' => $title,
            'url' => $url,
            'link_type' => $linkType,
        ]);

        $_SESSION['flash']['success'] = '链接更新成功！';
        $this->back();
    }

    public function delete($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        $linkModel = new Link();
        $link = $linkModel->find($id);

        if (!$link) {
            $_SESSION['flash']['error'] = '链接不存在';
            $this->redirect('/dashboard');
        }

        $pageModel = new Page();
        $page = $pageModel->find($link['page_id']);

        if (!$page || $page['user_id'] != $user['id']) {
            $_SESSION['flash']['error'] = '无权操作';
            $this->redirect('/dashboard');
        }

        $linkModel->delete($id);
        $_SESSION['flash']['success'] = '链接删除成功！';
        $this->back();
    }

    public function reorder()
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageId = $this->getPost('page_id');
        $order = $this->getPost('order', []);

        $pageModel = new Page();
        $page = $pageModel->find($pageId);

        if (!$page || $page['user_id'] != $user['id']) {
            $this->json(['success' => false], 403);
        }

        $linkModel = new Link();
        $linkModel->reorder($pageId, $order);

        $this->json(['success' => true]);
    }
}
