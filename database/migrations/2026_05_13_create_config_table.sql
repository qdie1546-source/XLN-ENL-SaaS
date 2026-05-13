-- LinkHub Platform Configuration Table
-- Used to store platform-wide settings

CREATE TABLE IF NOT EXISTS `config` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Configuration key',
    `value` TEXT NOT NULL COMMENT 'Configuration value',
    `type` ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string' COMMENT 'Value type',
    `group` VARCHAR(50) DEFAULT 'general' COMMENT 'Configuration group',
    `label` VARCHAR(100) DEFAULT NULL COMMENT 'Display label',
    `description` VARCHAR(255) DEFAULT NULL COMMENT 'Description',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_key` (`key`),
    INDEX `idx_group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Platform configuration table';

-- Insert default configurations
INSERT INTO `config` (`key`, `value`, `type`, `group`, `label`, `description`) VALUES
('site_name', 'LinkHub', 'string', 'general', '站点名称', '平台显示的名称'),
('site_logo', '', 'string', 'general', '站点 Logo', '平台 Logo 图片 URL'),
('site_description', '社交链接聚合平台', 'string', 'general', '站点描述', '平台简短描述'),
('ai_enabled', '1', 'boolean', 'ai', '启用 AI 功能', '是否启用 AI 智能生成功能'),
('ai_model', 'gpt-3.5-turbo', 'string', 'ai', 'AI 模型', '使用的 AI 模型'),
('maintenance_mode', '0', 'boolean', 'system', '维护模式', '是否开启网站维护模式'),
('allow_registration', '1', 'boolean', 'user', '允许注册', '是否允许新用户注册');
