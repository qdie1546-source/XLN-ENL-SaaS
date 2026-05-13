<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Libraries\Config;
use App\Libraries\Database;

class ThemeController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');
        $themes = $db->fetchAll("SELECT * FROM `{$prefix}themes` ORDER BY `id`");

        $this->view('admin/themes', [
            'themes' => $themes,
            'currentSection' => 'themes'
        ]);
    }

    public function edit($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        if (!$user['is_admin']) {
            $_SESSION['flash']['error'] = '无权限访问';
            $this->redirect('/');
        }

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');
        $theme = $db->fetch("SELECT * FROM `{$prefix}themes` WHERE `id` = ?", [$id]);

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/admin/themes');
        }

        $this->view('admin/themes-edit', [
            'theme' => $theme,
            'currentSection' => 'themes'
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

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');
        $theme = $db->fetch("SELECT * FROM `{$prefix}themes` WHERE `id` = ?", [$id]);

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/admin/themes');
        }

        $name = $this->getPost('name', $theme['name']);
        $description = $this->getPost('description', $theme['description'] ?? '');
        $cssContent = $this->getPost('css_content', $theme['css_content'] ?? '');
        $isActive = $this->getPost('is_active') ? 1 : 0;

        $db->execute(
            "UPDATE `{$prefix}themes` SET `name` = ?, `description` = ?, `css_content` = ?, `is_active` = ? WHERE `id` = ?",
            [$name, $description, $cssContent, $isActive, $id]
        );

        $_SESSION['flash']['success'] = '主题「' . htmlspecialchars($name) . '」更新成功';
        $this->redirect('/admin/themes');
    }
}
