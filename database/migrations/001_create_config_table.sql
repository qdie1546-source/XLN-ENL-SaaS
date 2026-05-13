-- LinkHub Platform Configuration
-- SQLite Database for Static Site Configuration

-- Create config table
CREATE TABLE IF NOT EXISTS `config` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `key` TEXT NOT NULL UNIQUE,
    `value` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert default configurations
INSERT OR REPLACE INTO `config` (`key`, `value`) VALUES
('site_name', 'LinkHub'),
('site_logo', ''),
('site_description', '社交链接聚合平台'),
('ai_enabled', '1'),
('ai_model', 'gpt-3.5-turbo'),
('maintenance_mode', '0'),
('allow_registration', '1');
