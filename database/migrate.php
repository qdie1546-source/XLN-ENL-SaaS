<?php
/**
 * LinkHub Database Migration
 * Run: php database/migrate.php
 */

define('BASE_PATH', dirname(__DIR__));

// Load .env
if (file_exists(BASE_PATH . '/.env')) {
    $env = parse_ini_file(BASE_PATH . '/.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}

require BASE_PATH . '/vendor/autoload.php';

\App\Libraries\Config::load();

$db = \App\Libraries\Database::getInstance();
$prefix = \App\Libraries\Config::get('database.prefix', 'lh_');

echo "Running migrations...\n";

// 1. Add avatar_url and bio to lh_users
foreach (['avatar_url TEXT', 'bio TEXT'] as $col) {
    try {
        $db->query("ALTER TABLE `{$prefix}users` ADD COLUMN $col");
        echo "  Added $col to {$prefix}users\n";
    } catch (\Exception $e) {
        if (strpos($e->getMessage(), 'duplicate column') !== false) {
            echo "  Skipped $col (already exists)\n";
        } else {
            echo "  Error adding $col: " . $e->getMessage() . "\n";
        }
    }
}

// 2. Add industry, company_size, is_active to lh_enterprise_profiles
foreach (['industry TEXT', 'company_size TEXT', 'is_active INTEGER DEFAULT 1'] as $col) {
    try {
        $db->query("ALTER TABLE `{$prefix}enterprise_profiles` ADD COLUMN $col");
        echo "  Added $col to {$prefix}enterprise_profiles\n";
    } catch (\Exception $e) {
        if (strpos($e->getMessage(), 'duplicate column') !== false) {
            echo "  Skipped $col (already exists)\n";
        } else {
            echo "  Error adding $col: " . $e->getMessage() . "\n";
        }
    }
}

// 3. Create lh_user_themes table
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}user_themes` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id` INTEGER NOT NULL,
    `name` TEXT NOT NULL,
    `slug` TEXT NOT NULL UNIQUE,
    `description` TEXT,
    `css_content` TEXT,
    `preview_css` TEXT,
    `price` DECIMAL(10,2) DEFAULT 0,
    `status` TEXT DEFAULT 'pending',
    `is_active` INTEGER DEFAULT 1,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");
echo "  Created {$prefix}user_themes\n";

// 4. Create lh_theme_purchases table
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}theme_purchases` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `buyer_id` INTEGER NOT NULL,
    `theme_id` INTEGER NOT NULL,
    `price_paid` DECIMAL(10,2) DEFAULT 0,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");
echo "  Created {$prefix}theme_purchases\n";

// 5. Create lh_config table
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}config` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `key` TEXT NOT NULL UNIQUE,
    `value` TEXT,
    `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");
echo "  Created {$prefix}config\n";

// 6. Seed lh_config with defaults
$defaults = [
    'site_name' => 'LinkHub',
    'site_description' => '社交链接聚合平台',
    'allow_registration' => '1',
];
foreach ($defaults as $key => $value) {
    $existing = $db->fetch("SELECT id FROM `{$prefix}config` WHERE `key` = ?", [$key]);
    if (!$existing) {
        $db->query("INSERT INTO `{$prefix}config` (`key`, `value`, `updated_at`) VALUES (?, ?, ?)", [$key, $value, date('Y-m-d H:i:s')]);
        echo "  Seeded config: $key = $value\n";
    }
}

// 7. Create lh_wallets table
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}wallets` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id` INTEGER NOT NULL UNIQUE,
    `balance` DECIMAL(10,2) DEFAULT 0.00,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");
echo "  Created {$prefix}wallets\n";

// 8. Create lh_transactions table
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}transactions` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id` INTEGER NOT NULL,
    `type` TEXT NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `related_id` INTEGER,
    `description` TEXT,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");
echo "  Created {$prefix}transactions\n";

// 9. Seed commission_rate config
$existing = $db->fetch("SELECT id FROM `{$prefix}config` WHERE `key` = 'commission_rate'");
if (!$existing) {
    $db->query("INSERT INTO `{$prefix}config` (`key`, `value`, `updated_at`) VALUES ('commission_rate', '20', ?)", [date('Y-m-d H:i:s')]);
    echo "  Seeded commission_rate = 20\n";
}

// 10. Seed lh_themes from config/themes.php
$themes = require __DIR__ . '/../config/themes.php';
foreach ($themes as $slug => $theme) {
    $existing = $db->fetch("SELECT id FROM `{$prefix}themes` WHERE `slug` = ?", [$slug]);
    if (!$existing) {
        $db->query(
            "INSERT INTO `{$prefix}themes` (`name`, `slug`, `css_content`, `is_free`, `is_active`, `created_at`) VALUES (?, ?, ?, ?, ?, ?)",
            [$theme['name'], $slug, '', $theme['is_free'] ? 1 : 0, $theme['is_active'] ? 1 : 0, date('Y-m-d H:i:s')]
        );
        echo "  Seeded theme: {$theme['name']}\n";
    }
}

// 11. Add expires_at to lh_enterprise_profiles
try {
    $db->query("ALTER TABLE `{$prefix}enterprise_profiles` ADD COLUMN expires_at TEXT");
    echo "  Added expires_at to {$prefix}enterprise_profiles\n";
} catch (\Exception $e) {
    if (strpos($e->getMessage(), 'duplicate column') !== false) {
        echo "  Skipped expires_at (already exists)\n";
    } else {
        echo "  Error adding expires_at: " . $e->getMessage() . "\n";
    }
}

// 12. Create lh_plans table
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}plans` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` TEXT NOT NULL,
    `slug` TEXT NOT NULL UNIQUE,
    `description` TEXT,
    `price` DECIMAL(10,2) DEFAULT 0,
    `type` TEXT DEFAULT 'enterprise',
    `page_limit` INTEGER DEFAULT 0,
    `duration_days` INTEGER DEFAULT 365,
    `is_active` INTEGER DEFAULT 1,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");
echo "  Created {$prefix}plans\n";

// 13. Create lh_plan_purchases table
$db->query("CREATE TABLE IF NOT EXISTS `{$prefix}plan_purchases` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id` INTEGER NOT NULL,
    `plan_id` INTEGER NOT NULL,
    `price_paid` DECIMAL(10,2) NOT NULL,
    `expires_at` TEXT,
    `created_at` TEXT DEFAULT CURRENT_TIMESTAMP
)");
echo "  Created {$prefix}plan_purchases\n";

// 14. Seed AI config defaults
$aiDefaults = [
    'ai_enabled' => '1',
    'ai_model' => 'gpt-3.5-turbo',
    'ai_api_key' => '',
    'ai_api_endpoint' => '',
    'ai_daily_limit' => '100',
];
foreach ($aiDefaults as $key => $value) {
    $existing = $db->fetch("SELECT id FROM `{$prefix}config` WHERE `key` = ?", [$key]);
    if (!$existing) {
        $db->query("INSERT INTO `{$prefix}config` (`key`, `value`, `updated_at`) VALUES (?, ?, ?)", [$key, $value, date('Y-m-d H:i:s')]);
        echo "  Seeded config: $key\n";
    }
}

// 15. Seed payment config defaults
$paymentDefaults = [
    'payment_alipay_enabled' => '0',
    'payment_alipay_qr' => '',
    'payment_wechat_enabled' => '0',
    'payment_wechat_qr' => '',
];
foreach ($paymentDefaults as $key => $value) {
    $existing = $db->fetch("SELECT id FROM `{$prefix}config` WHERE `key` = ?", [$key]);
    if (!$existing) {
        $db->query("INSERT INTO `{$prefix}config` (`key`, `value`, `updated_at`) VALUES (?, ?, ?)", [$key, $value, date('Y-m-d H:i:s')]);
        echo "  Seeded config: $key\n";
    }
}

// 16. Theme tags
try {
    $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}theme_tags` (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT,
        `name` TEXT NOT NULL,
        `slug` TEXT NOT NULL UNIQUE,
        `created_at` TEXT DEFAULT CURRENT_TIMESTAMP
    )");
    echo "  Created {$prefix}theme_tags\n";
} catch (\Exception $e) { echo "  Skip {$prefix}theme_tags: " . $e->getMessage() . "\n"; }

try {
    $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}theme_tag_relations` (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT,
        `theme_id` INTEGER NOT NULL,
        `tag_id` INTEGER NOT NULL,
        `theme_type` TEXT NOT NULL DEFAULT 'system'
    )");
    echo "  Created {$prefix}theme_tag_relations\n";
} catch (\Exception $e) { echo "  Skip {$prefix}theme_tag_relations: " . $e->getMessage() . "\n"; }

// Seed default tags
$defaultTags = ['简约', '科技', '渐变', '暗色', '卡片', '圆角', '商务', '清新', '复古'];
foreach ($defaultTags as $tagName) {
    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $tagName)) ?: 'tag';
    $existing = $db->fetch("SELECT id FROM `{$prefix}theme_tags` WHERE `slug` = ?", [$slug]);
    if (!$existing) {
        $db->query("INSERT INTO `{$prefix}theme_tags` (`name`, `slug`, `created_at`) VALUES (?, ?, ?)", [$tagName, $slug, date('Y-m-d H:i:s')]);
    }
}
echo "  Seeded default tags\n";

// Seed Epay config defaults
$epayDefaults = [
    'epay_enabled' => '0',
    'epay_pid' => '',
    'epay_key' => '',
    'epay_api_url' => '',
];
foreach ($epayDefaults as $key => $value) {
    $existing = $db->fetch("SELECT id FROM `{$prefix}config` WHERE `key` = ?", [$key]);
    if (!$existing) {
        $db->query("INSERT INTO `{$prefix}config` (`key`, `value`, `updated_at`) VALUES (?, ?, ?)", [$key, $value, date('Y-m-d H:i:s')]);
        echo "  Seeded config: $key\n";
    }
}

try {
    $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}payment_requests` (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT,
        `user_id` INTEGER NOT NULL,
        `order_type` TEXT NOT NULL,
        `order_id` INTEGER,
        `provider` TEXT NOT NULL DEFAULT 'epay',
        `out_trade_no` TEXT NOT NULL UNIQUE,
        `title` TEXT,
        `amount` DECIMAL(10,2) NOT NULL,
        `currency` TEXT NOT NULL DEFAULT 'CNY',
        `status` TEXT NOT NULL DEFAULT 'pending',
        `provider_trade_no` TEXT,
        `metadata` TEXT,
        `created_at` TEXT DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TEXT DEFAULT CURRENT_TIMESTAMP
    )");
    echo "  Created {$prefix}payment_requests\n";
} catch (\Exception $e) {
    echo "  Skip {$prefix}payment_requests: " . $e->getMessage() . "\n";
}

// Seed commission_rate
$existing = $db->fetch("SELECT id FROM `{$prefix}config` WHERE `key` = 'commission_rate'");
if (!$existing) {
    $db->query("INSERT INTO `{$prefix}config` (`key`, `value`, `updated_at`) VALUES ('commission_rate', '20', ?)", [date('Y-m-d H:i:s')]);
    echo "  Seeded config: commission_rate\n";
}

// 17. Add link_type to lh_links
try {
    $db->query("ALTER TABLE `{$prefix}links` ADD COLUMN `link_type` TEXT DEFAULT 'link'");
    echo "  Added link_type to {$prefix}links\n";
} catch (\Exception $e) { echo "  Skip link_type: " . $e->getMessage() . "\n"; }

echo "Migration complete.\n";
