<?php

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

if (file_exists(BASE_PATH . '/.env')) {
    $env = parse_ini_file(BASE_PATH . '/.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}

LinkHub\Libraries\Config::load();

use LinkHub\Libraries\Database;

$db = Database::getInstance();

$prefix = LinkHub\Libraries\Config::get('database.prefix', 'lh_');

// SQLite-compatible schema
$db->query("PRAGMA journal_mode=WAL");
$db->query("PRAGMA foreign_keys=ON");

// users table
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}users` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `email` TEXT NOT NULL UNIQUE,
    `password` TEXT NOT NULL,
    `name` TEXT DEFAULT NULL,
    `phone` TEXT DEFAULT NULL,
    `user_type` TEXT DEFAULT 'individual',
    `is_active` INTEGER DEFAULT 1,
    `is_admin` INTEGER DEFAULT 0,
    `status` TEXT DEFAULT 'active',
    `email_verified` INTEGER DEFAULT 0,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");

// enterprise_profiles
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}enterprise_profiles` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id` INTEGER NOT NULL UNIQUE,
    `company_name` TEXT NOT NULL,
    `logo_url` TEXT DEFAULT NULL,
    `custom_domain` TEXT DEFAULT NULL UNIQUE,
    `brand_color` TEXT DEFAULT '#000000',
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");

// pages
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}pages` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id` INTEGER NOT NULL,
    `slug` TEXT NOT NULL UNIQUE,
    `title` TEXT DEFAULT 'My Links',
    `description` TEXT,
    `avatar_url` TEXT,
    `bio` TEXT,
    `theme_id` INTEGER DEFAULT 1,
    `custom_css` TEXT,
    `custom_bg` TEXT,
    `seo_title` TEXT,
    `seo_description` TEXT,
    `is_published` INTEGER DEFAULT 1,
    `view_count` INTEGER DEFAULT 0,
    `click_count` INTEGER DEFAULT 0,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");

// links
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}links` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `page_id` INTEGER NOT NULL,
    `title` TEXT NOT NULL,
    `url` TEXT NOT NULL,
    `icon` TEXT,
    `position` INTEGER DEFAULT 0,
    `link_type` TEXT DEFAULT 'url',
    `is_active` INTEGER DEFAULT 1,
    `click_count` INTEGER DEFAULT 0,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");

// themes
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}themes` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` TEXT NOT NULL,
    `slug` TEXT NOT NULL UNIQUE,
    `preview_image` TEXT,
    `css_content` TEXT NOT NULL,
    `is_free` INTEGER DEFAULT 1,
    `is_active` INTEGER DEFAULT 1,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");

// statistics
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}statistics` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `link_id` INTEGER,
    `page_id` INTEGER NOT NULL,
    `ip_address` TEXT NOT NULL,
    `country` TEXT,
    `city` TEXT,
    `device_type` TEXT DEFAULT 'desktop',
    `browser` TEXT,
    `os` TEXT,
    `referer` TEXT,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");

// ai_settings
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}ai_settings` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `provider` TEXT DEFAULT 'openai',
    `api_key` TEXT NOT NULL,
    `api_endpoint` TEXT,
    `model` TEXT DEFAULT 'gpt-3.5-turbo',
    `is_active` INTEGER DEFAULT 1,
    `daily_limit` INTEGER DEFAULT 100,
    `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");

// ai_usage_logs
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}ai_usage_logs` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id` INTEGER NOT NULL,
    `prompt_tokens` INTEGER DEFAULT 0,
    `completion_tokens` INTEGER DEFAULT 0,
    `total_tokens` INTEGER DEFAULT 0,
    `cost` REAL DEFAULT 0,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");

// install_config
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}install_config` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `install_key` TEXT NOT NULL UNIQUE,
    `site_name` TEXT DEFAULT 'LinkHub',
    `site_url` TEXT NOT NULL,
    `admin_email` TEXT NOT NULL,
    `admin_password` TEXT NOT NULL,
    `db_host` TEXT NOT NULL,
    `db_port` INTEGER DEFAULT 3306,
    `db_name` TEXT NOT NULL,
    `db_user` TEXT NOT NULL,
    `db_password` TEXT NOT NULL,
    `db_prefix` TEXT DEFAULT 'lh_',
    `is_installed` INTEGER DEFAULT 0,
    `installed_at` TEXT,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");

echo "Tables created.\n";

$adminPassword = password_hash('admin123456', PASSWORD_DEFAULT);

$db->insert("{$prefix}users", [
    'email' => 'admin@linkhub.com',
    'password' => $adminPassword,
    'name' => '管理员',
    'user_type' => 'individual',
    'is_admin' => 1,
    'is_active' => 1,
    'status' => 'active',
    'email_verified' => 1,
]);

$userPassword = password_hash('test123456', PASSWORD_DEFAULT);

$db->insert("{$prefix}users", [
    'email' => 'test@linkhub.com',
    'password' => $userPassword,
    'name' => '测试用户',
    'user_type' => 'individual',
    'is_admin' => 0,
    'is_active' => 1,
    'status' => 'active',
    'email_verified' => 1,
]);

echo "Users created.\n";

$themes = [
    ['name' => 'Minimal', 'slug' => 'minimal', 'css_content' => ':root { --primary: #3b82f6; --bg: #ffffff; --text: #1f2937; }', 'is_free' => 1, 'is_active' => 1],
    ['name' => 'Gradient', 'slug' => 'gradient', 'css_content' => ':root { --primary: #6366f1; --bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%); --text: #ffffff; }', 'is_free' => 1, 'is_active' => 1],
    ['name' => 'Card', 'slug' => 'card', 'css_content' => ':root { --primary: #8b5cf6; --bg: #f8fafc; --text: #1e293b; }', 'is_free' => 1, 'is_active' => 1],
    ['name' => 'Dark', 'slug' => 'dark', 'css_content' => ':root { --primary: #60a5fa; --bg: #0f172a; --text: #e2e8f0; }', 'is_free' => 1, 'is_active' => 1],
    ['name' => 'Vintage', 'slug' => 'vintage', 'css_content' => ':root { --primary: #d97706; --bg: #fef3c7; --text: #78350f; }', 'is_free' => 1, 'is_active' => 1],
];

foreach ($themes as $theme) {
    $db->insert("{$prefix}themes", $theme);
}

echo "Themes created.\n";

$db->insert("{$prefix}pages", [
    'user_id' => 1,
    'slug' => 'admin',
    'title' => '管理员',
    'bio' => 'LinkHub 管理员，管理一切链接',
    'theme_id' => 1,
    'is_published' => 1,
    'view_count' => 128,
    'click_count' => 45,
]);

$db->insert("{$prefix}pages", [
    'user_id' => 2,
    'slug' => 'testuser',
    'title' => '测试用户',
    'bio' => '这是一个测试的个人页面',
    'theme_id' => 2,
    'is_published' => 1,
    'view_count' => 56,
    'click_count' => 23,
]);

$db->insert("{$prefix}pages", [
    'user_id' => 2,
    'slug' => 'testuser-dark',
    'title' => '测试暗色主题',
    'bio' => '暗色风格页面预览',
    'theme_id' => 4,
    'is_published' => 1,
    'view_count' => 12,
    'click_count' => 5,
]);

echo "Pages created.\n";

$links = [
    ['page_id' => 1, 'title' => '微博', 'url' => 'https://weibo.com', 'position' => 0, 'link_type' => 'url', 'is_active' => 1, 'click_count' => 15],
    ['page_id' => 1, 'title' => 'GitHub', 'url' => 'https://github.com', 'position' => 1, 'link_type' => 'url', 'is_active' => 1, 'click_count' => 10],
    ['page_id' => 1, 'title' => '知乎', 'url' => 'https://zhihu.com', 'position' => 2, 'link_type' => 'url', 'is_active' => 1, 'click_count' => 8],
    ['page_id' => 1, 'title' => '邮箱', 'url' => 'mailto:admin@linkhub.com', 'position' => 3, 'link_type' => 'url', 'is_active' => 1, 'click_count' => 12],
    ['page_id' => 2, 'title' => '微信', 'url' => 'https://weixin.qq.com', 'position' => 0, 'link_type' => 'url', 'is_active' => 1, 'click_count' => 10],
    ['page_id' => 2, 'title' => 'B站', 'url' => 'https://bilibili.com', 'position' => 1, 'link_type' => 'url', 'is_active' => 1, 'click_count' => 8],
    ['page_id' => 2, 'title' => '抖音', 'url' => 'https://douyin.com', 'position' => 2, 'link_type' => 'url', 'is_active' => 1, 'click_count' => 5],
    ['page_id' => 3, 'title' => 'Twitter', 'url' => 'https://twitter.com', 'position' => 0, 'link_type' => 'url', 'is_active' => 1, 'click_count' => 3],
    ['page_id' => 3, 'title' => 'Instagram', 'url' => 'https://instagram.com', 'position' => 1, 'link_type' => 'url', 'is_active' => 1, 'click_count' => 2],
];

foreach ($links as $link) {
    $db->insert("{$prefix}links", $link);
}

echo "Links created.\n";

$statData = [
    ['page_id' => 1, 'link_id' => 1, 'ip_address' => '192.168.1.1', 'device_type' => 'desktop', 'browser' => 'Chrome'],
    ['page_id' => 1, 'link_id' => 1, 'ip_address' => '10.0.0.1', 'device_type' => 'mobile', 'browser' => 'Safari'],
    ['page_id' => 1, 'link_id' => 2, 'ip_address' => '172.16.0.1', 'device_type' => 'desktop', 'browser' => 'Firefox'],
    ['page_id' => 1, 'link_id' => null, 'ip_address' => '192.168.1.100', 'device_type' => 'mobile', 'browser' => 'Chrome'],
    ['page_id' => 2, 'link_id' => 5, 'ip_address' => '10.0.0.2', 'device_type' => 'desktop', 'browser' => 'Edge'],
    ['page_id' => 2, 'link_id' => null, 'ip_address' => '172.16.0.2', 'device_type' => 'tablet', 'browser' => 'Safari'],
];

foreach ($statData as $stat) {
    $db->insert("{$prefix}statistics", $stat);
}

echo "Statistics created.\n";

$db->insert("{$prefix}install_config", [
    'install_key' => bin2hex(random_bytes(32)),
    'site_name' => 'LinkHub',
    'site_url' => 'http://localhost:8000',
    'admin_email' => 'admin@linkhub.com',
    'admin_password' => $adminPassword,
    'db_host' => 'localhost',
    'db_port' => 3306,
    'db_name' => 'linkhub_dev',
    'db_user' => 'root',
    'db_password' => '',
    'db_prefix' => 'lh_',
    'is_installed' => 1,
    'installed_at' => date('Y-m-d H:i:s'),
]);

echo "Install config created.\n";
echo "\nSetup complete!\n";
echo "---\n";
echo "Admin:  admin@linkhub.com / admin123456\n";
echo "User:   test@linkhub.com  / test123456\n";
echo "Pages:  /admin, /testuser, /testuser-dark\n";
