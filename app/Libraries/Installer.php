<?php

namespace App\Libraries;

class Installer
{
    const CONFIG_FILE = BASE_PATH . '/config/install.php';
    const INSTALL_FLAG_TABLE = 'lh_install_config';

    /**
     * 检查系统是否已安装
     */
    public static function isInstalled(): bool
    {
        // 检查安装标记文件
        if (file_exists(self::CONFIG_FILE)) {
            $config = require self::CONFIG_FILE;
            if (isset($config['is_installed']) && $config['is_installed'] === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检查环境要求
     */
    public static function checkEnvironment(): array
    {
        $checks = [];

        // PHP 版本
        $phpVersion = phpversion();
        $checks['php_version'] = [
            'name' => 'PHP 版本',
            'required' => '7.4.0',
            'current' => $phpVersion,
            'status' => version_compare($phpVersion, '7.4.0', '>='),
            'message' => sprintf('当前版本: %s (需要 >= 7.4.0)', $phpVersion)
        ];

        // PDO MySQL 扩展
        $checks['pdo_mysql'] = [
            'name' => 'PDO MySQL 扩展',
            'status' => extension_loaded('pdo_mysql') || extension_loaded('pdo'),
            'message' => extension_loaded('pdo_mysql') ? '已启用' : (extension_loaded('pdo') ? '已启用 (通用)' : '未启用')
        ];

        // GD 扩展
        $checks['gd'] = [
            'name' => 'GD 扩展',
            'status' => extension_loaded('gd'),
            'message' => extension_loaded('gd') ? '已启用' : '未启用'
        ];

        // mbstring 扩展
        $checks['mbstring'] = [
            'name' => 'mbstring 扩展',
            'status' => extension_loaded('mbstring'),
            'message' => extension_loaded('mbstring') ? '已启用' : '未启用'
        ];

        // 目录写入权限
        $dirs = [
            BASE_PATH . '/config' => 'config 目录',
            BASE_PATH . '/storage' => 'storage 目录',
            BASE_PATH . '/database' => 'database 目录',
        ];

        foreach ($dirs as $dir => $name) {
            $checks['dir_' . basename($dir)] = [
                'name' => $name . ' (写入权限)',
                'status' => is_writable($dir) || @mkdir($dir, 0755, true),
                'message' => is_writable($dir) ? '可写' : '不可写'
            ];
        }

        return $checks;
    }

    /**
     * 检查所有环境要求是否满足
     */
    public static function envChecksPass(): bool
    {
        $checks = self::checkEnvironment();
        foreach ($checks as $check) {
            if (!$check['status']) {
                return false;
            }
        }
        return true;
    }

    /**
     * 测试数据库连接
     */
    public static function testDatabaseConnection($config): bool
    {
        try {
            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4",
                $config['host'],
                $config['port'],
                $config['name']
            );

            $pdo = new \PDO(
                $dsn,
                $config['user'],
                $config['pass'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_TIMEOUT => 5
                ]
            );

            return $pdo !== null;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * 创建安装配置
     */
    public static function saveInstallConfig($data): bool
    {
        $config = [
            'is_installed' => true,
            'site_name' => $data['site_name'] ?? 'LinkHub',
            'site_url' => $data['site_url'] ?? '',
            'admin_email' => $data['admin_email'] ?? '',
            'db_config' => [
                'host' => $data['db_host'] ?? 'localhost',
                'port' => $data['db_port'] ?? 3306,
                'name' => $data['db_name'] ?? 'linkhub_db',
                'user' => $data['db_user'] ?? 'root',
                'pass' => $data['db_pass'] ?? '',
                'prefix' => $data['db_prefix'] ?? 'lh_'
            ],
            'installed_at' => date('Y-m-d H:i:s'),
        ];

        $configCode = '<?php' . "\n\nreturn " . var_export($config, true) . ";\n";

        if (!file_exists(dirname(self::CONFIG_FILE))) {
            mkdir(dirname(self::CONFIG_FILE), 0755, true);
        }

        return file_put_contents(self::CONFIG_FILE, $configCode) !== false;
    }

    /**
     * 生成 .env 文件
     */
    public static function generateEnvFile($data): bool
    {
        $envContent = sprintf(
            "# 站点配置\n" .
            "APP_NAME=%s\n" .
            "APP_URL=%s\n" .
            "APP_ENV=production\n" .
            "APP_DEBUG=false\n\n" .
            "# 数据库配置\n" .
            "DB_DRIVER=mysql\n" .
            "DB_HOST=%s\n" .
            "DB_PORT=%s\n" .
            "DB_NAME=%s\n" .
            "DB_USER=%s\n" .
            "DB_PASS=%s\n" .
            "DB_PREFIX=%s\n\n" .
            "# AI 配置\n" .
            "AI_PROVIDER=openai\n" .
            "AI_API_KEY=\n" .
            "AI_API_ENDPOINT=\n" .
            "AI_MODEL=gpt-3.5-turbo\n" .
            "AI_DAILY_LIMIT=100\n\n" .
            "# 会话配置\n" .
            "SESSION_LIFETIME=7200\n\n" .
            "# 缓存配置\n" .
            "CACHE_DRIVER=file\n",
            $data['site_name'] ?? 'LinkHub',
            $data['site_url'] ?? 'http://localhost',
            $data['db_host'] ?? 'localhost',
            $data['db_port'] ?? 3306,
            $data['db_name'] ?? 'linkhub_db',
            $data['db_user'] ?? 'root',
            $data['db_pass'] ?? '',
            $data['db_prefix'] ?? 'lh_'
        );

        return file_put_contents(BASE_PATH . '/.env', $envContent) !== false;
    }

    /**
     * 初始化数据库表
     */
    public static function initializeDatabase($config): bool
    {
        try {
            $pdo = new \PDO(
                sprintf("mysql:host=%s;port=%s;charset=utf8mb4", $config['host'], $config['port']),
                $config['user'],
                $config['pass']
            );

            // 创建数据库
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$config['name']}`");

            // 创建表
            return self::createTables($pdo, $config['prefix']);

        } catch (\PDOException $e) {
            error_log('Database initialization failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 创建数据表
     */
    private static function createTables($pdo, $prefix): bool
    {
        $tables = self::getTableSchemas($prefix);

        foreach ($tables as $sql) {
            try {
                $pdo->exec($sql);
            } catch (\PDOException $e) {
                error_log('Table creation failed: ' . $e->getMessage());
                return false;
            }
        }

        return true;
    }

    /**
     * 获取数据表结构 SQL
     */
    private static function getTableSchemas($prefix): array
    {
        return [
            // 用户表
            "CREATE TABLE IF NOT EXISTS `{$prefix}users` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `email` VARCHAR(255) UNIQUE NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `name` VARCHAR(255),
                `is_admin` TINYINT(1) DEFAULT 0,
                `is_active` TINYINT(1) DEFAULT 1,
                `user_type` ENUM('free', 'pro', 'enterprise') DEFAULT 'free',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email),
                INDEX idx_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            // 企业信息表
            "CREATE TABLE IF NOT EXISTS `{$prefix}enterprise_profiles` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT UNSIGNED NOT NULL,
                `company_name` VARCHAR(255),
                `custom_domain` VARCHAR(255) UNIQUE,
                `is_active` TINYINT(1) DEFAULT 1,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES `{$prefix}users`(id) ON DELETE CASCADE,
                UNIQUE KEY uk_user (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            // 用户页面表
            "CREATE TABLE IF NOT EXISTS `{$prefix}pages` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT UNSIGNED NOT NULL,
                `slug` VARCHAR(255) UNIQUE NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `bio` TEXT,
                `theme_id` INT UNSIGNED,
                `view_count` INT DEFAULT 0,
                `click_count` INT DEFAULT 0,
                `is_active` TINYINT(1) DEFAULT 1,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES `{$prefix}users`(id) ON DELETE CASCADE,
                UNIQUE KEY uk_slug (slug),
                INDEX idx_user (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            // 链接表
            "CREATE TABLE IF NOT EXISTS `{$prefix}links` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `page_id` INT UNSIGNED NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `url` VARCHAR(2048) NOT NULL,
                `position` INT DEFAULT 0,
                `link_type` VARCHAR(50),
                `click_count` INT DEFAULT 0,
                `is_active` TINYINT(1) DEFAULT 1,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (page_id) REFERENCES `{$prefix}pages`(id) ON DELETE CASCADE,
                INDEX idx_page (page_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            // 主题表
            "CREATE TABLE IF NOT EXISTS `{$prefix}themes` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `slug` VARCHAR(255) UNIQUE NOT NULL,
                `css_content` LONGTEXT,
                `is_free` TINYINT(1) DEFAULT 1,
                `is_active` TINYINT(1) DEFAULT 1,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_slug (slug)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            // 统计表
            "CREATE TABLE IF NOT EXISTS `{$prefix}statistics` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `link_id` INT UNSIGNED,
                `page_id` INT UNSIGNED,
                `ip_address` VARCHAR(45),
                `device_type` VARCHAR(50),
                `browser` VARCHAR(100),
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_link (link_id),
                INDEX idx_page (page_id),
                INDEX idx_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            // AI 配置表
            "CREATE TABLE IF NOT EXISTS `{$prefix}ai_settings` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `provider` VARCHAR(50) UNIQUE NOT NULL,
                `api_key` VARCHAR(500),
                `model` VARCHAR(100),
                `daily_limit` INT DEFAULT 100,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            // AI 用量日志表
            "CREATE TABLE IF NOT EXISTS `{$prefix}ai_usage_logs` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT UNSIGNED NOT NULL,
                `prompt_tokens` INT DEFAULT 0,
                `completion_tokens` INT DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES `{$prefix}users`(id) ON DELETE CASCADE,
                INDEX idx_user (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            // 安装配置表
            "CREATE TABLE IF NOT EXISTS `{$prefix}install_config` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `install_key` VARCHAR(255) UNIQUE,
                `site_url` VARCHAR(255),
                `is_installed` TINYINT(1) DEFAULT 0,
                `installed_at` TIMESTAMP NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        ];
    }

    /**
     * 创建管理员账户
     */
    public static function createAdminUser($data, $config): bool
    {
        try {
            $db = new Database([
                'driver' => 'mysql',
                'host' => $config['host'],
                'port' => $config['port'],
                'name' => $config['name'],
                'user' => $config['user'],
                'pass' => $config['pass']
            ]);

            $password = password_hash($data['admin_password'], PASSWORD_BCRYPT);

            $sql = "INSERT INTO `{$config['prefix']}users` (email, password, name, is_admin, is_active) 
                    VALUES (?, ?, ?, 1, 1)";

            $result = $db->query($sql, [
                $data['admin_email'],
                $password,
                $data['admin_name'] ?? 'Administrator'
            ]);

            return $result !== false;
        } catch (\Exception $e) {
            error_log('Admin user creation failed: ' . $e->getMessage());
            return false;
        }
    }
}
