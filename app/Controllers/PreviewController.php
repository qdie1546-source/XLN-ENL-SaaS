<?php

namespace App\Controllers;

use App\Libraries\Config;
use App\Libraries\Database;

class PreviewController extends Controller
{
    public function theme($slug)
    {
        $db = Database::getInstance();
        $prefix = Config::get('database.prefix', 'lh_');

        $theme = $db->fetch("SELECT * FROM `{$prefix}themes` WHERE `slug` = ? AND `is_active` = 1", [$slug]);

        if (!$theme) {
            $theme = $db->fetch("SELECT * FROM `{$prefix}user_themes` WHERE `slug` = ? AND `status` = 'approved' AND `is_active` = 1", [$slug]);
        }

        if (!$theme) {
            http_response_code(404);
            echo '<h1>Theme not found</h1>';
            exit;
        }

        header('Content-Type: text/html; charset=utf-8');
        ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>主题预览 - <?php echo htmlspecialchars($theme['name']); ?></title>
    <style>
        <?php echo $theme['css_content'] ?? ''; ?>
    </style>
</head>
<body>
    <div class="profile-section">
        <div class="avatar"></div>
        <h1 class="name">预览用户</h1>
        <p class="bio">这是一段示例简介，用于展示主题效果</p>
    </div>
    <div class="links">
        <a href="#" class="link-card">微信</a>
        <a href="#" class="link-card">微博</a>
        <a href="#" class="link-card">小红书</a>
        <a href="#" class="link-card">抖音</a>
    </div>
</body>
</html>
        <?php
        exit;
    }
}
