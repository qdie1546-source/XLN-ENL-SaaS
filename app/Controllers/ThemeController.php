<?php

namespace App\Controllers;

use App\Libraries\Config;
use App\Libraries\Database;
use LinkHub\Models\Page;
use LinkHub\Models\UserTheme;
use LinkHub\Models\ThemePurchase;
use LinkHub\Models\Tag;

class ThemeController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageModel = new Page();
        $pages = $pageModel->findByUser($user['id']);

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');
        $dbThemes = $db->fetchAll("SELECT * FROM `{$prefix}themes` WHERE `is_active` = 1");
        $configThemes = Config::get('themes');

        $themes = [];
        foreach ($dbThemes as $theme) {
            $slug = $theme['slug'];
            $configTheme = $configThemes[$slug] ?? [];
            $themes[] = [
                'id' => $theme['id'],
                'name' => $theme['name'],
                'slug' => $slug,
                'description' => $theme['description'] ?? $configTheme['description'] ?? '',
                'is_free' => $theme['is_free'] ?? 1,
                'is_active' => $theme['is_active'] ?? 1,
                'preview' => $theme['preview_image'] ?? $configTheme['preview'] ?? '',
                'source' => 'system',
                'price' => 0,
                'author_name' => 'LinkHub',
            ];
        }

        // Merge user-uploaded approved themes
        $userThemeModel = new UserTheme();
        $marketThemes = $userThemeModel->marketThemes();
        $tagModel = new Tag();
        foreach ($marketThemes as $ut) {
            $tags = $tagModel->forTheme($ut['id'], 'user');
            $themes[] = [
                'id' => $ut['id'],
                'name' => $ut['name'],
                'slug' => $ut['slug'],
                'description' => $ut['description'] ?? '',
                'is_free' => floatval($ut['price'] ?? 0) == 0,
                'is_active' => $ut['is_active'] ?? 1,
                'preview' => '',
                'source' => 'user',
                'price' => floatval($ut['price'] ?? 0),
                'author_name' => $ut['author_name'] ?? '未知',
                'tags' => $tags,
            ];
        }

        $this->view('themes/index', [
            'themes' => $themes,
            'pages' => $pages,
        ]);
    }

    public function preview($slug)
    {
        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');
        $theme = $db->fetch("SELECT * FROM `{$prefix}themes` WHERE `slug` = ? AND `is_active` = 1", [$slug]);

        if (!$theme) {
            $theme = $db->fetch("SELECT * FROM `{$prefix}user_themes` WHERE `slug` = ? AND `status` = 'approved' AND `is_active` = 1", [$slug]);
        }

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/themes');
        }

        $this->view('themes/preview', ['theme' => $theme, 'slug' => $slug]);
    }

    public function applyTo($slug)
    {
        $this->requireAuth();
        $user = $this->auth();

        $pageId = $_POST['page_id'] ?? null;
        if (!$pageId) {
            $_SESSION['flash']['error'] = '请选择要应用主题的页面';
            $this->redirect('/themes');
        }

        $pageModel = new Page();
        $page = $pageModel->find($pageId);

        if (!$page || $page['user_id'] != $user['id']) {
            $_SESSION['flash']['error'] = '页面不存在';
            $this->redirect('/themes');
        }

        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');

        // Try system themes first
        $theme = $db->fetch("SELECT * FROM `{$prefix}themes` WHERE `slug` = ?", [$slug]);
        $source = 'system';

        if (!$theme) {
            // Try user themes
            $theme = $db->fetch("SELECT * FROM `{$prefix}user_themes` WHERE `slug` = ? AND `status` = 'approved' AND `is_active` = 1", [$slug]);
            $source = 'user';
        }

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/themes');
        }

        if ($source === 'system') {
            $pageModel->update($pageId, ['theme_id' => $theme['id']]);
        } else {
            // Check purchase permission
            $purchaseModel = new ThemePurchase();
            $canApply = $theme['user_id'] == $user['id']
                || $purchaseModel->hasPurchased($user['id'], $theme['id'])
                || (floatval($theme['price'] ?? 0) == 0);

            if (!$canApply) {
                $_SESSION['flash']['error'] = '请先获取该主题';
                $this->redirect('/themes');
            }

            $pageModel->update($pageId, ['custom_css' => $theme['css_content']]);
        }

        $_SESSION['flash']['success'] = '主题「' . htmlspecialchars($theme['name']) . '」已应用到 ' . htmlspecialchars($page['title']);
        $this->redirect('/themes');
    }
}
