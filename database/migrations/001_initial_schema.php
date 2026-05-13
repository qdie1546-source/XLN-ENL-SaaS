<?php

return "
CREATE TABLE IF NOT EXISTS `{prefix}users` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `name` varchar(100) DEFAULT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `user_type` enum('individual','enterprise') DEFAULT 'individual',
    `is_active` tinyint(1) DEFAULT 1,
    `is_admin` tinyint(1) DEFAULT 0,
    `status` enum('active','suspended','inactive') DEFAULT 'active',
    `email_verified` tinyint(1) DEFAULT 0,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `{prefix}enterprise_profiles` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `company_name` varchar(255) NOT NULL,
    `logo_url` varchar(500) DEFAULT NULL,
    `custom_domain` varchar(255) DEFAULT NULL,
    `brand_color` varchar(7) DEFAULT '#000000',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_enterprise_user` (`user_id`),
    UNIQUE KEY `idx_enterprise_domain` (`custom_domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `{prefix}pages` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `slug` varchar(50) NOT NULL,
    `title` varchar(255) DEFAULT 'My Links',
    `description` text,
    `avatar_url` varchar(500),
    `bio` text,
    `theme_id` int unsigned DEFAULT 1,
    `custom_css` text,
    `custom_bg` varchar(500),
    `seo_title` varchar(255),
    `seo_description` text,
    `is_published` tinyint(1) DEFAULT 1,
    `view_count` int unsigned DEFAULT 0,
    `click_count` int unsigned DEFAULT 0,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_pages_slug` (`slug`),
    KEY `idx_pages_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `{prefix}links` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `page_id` bigint unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `url` varchar(500) NOT NULL,
    `icon` varchar(100),
    `position` int unsigned DEFAULT 0,
    `link_type` enum('url','image','video','audio','payment','map') DEFAULT 'url',
    `is_active` tinyint(1) DEFAULT 1,
    `click_count` int unsigned DEFAULT 0,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_links_page` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `{prefix}themes` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `slug` varchar(50) NOT NULL,
    `preview_image` varchar(500),
    `css_content` text NOT NULL,
    `is_free` tinyint(1) DEFAULT 1,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_themes_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `{prefix}statistics` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `link_id` bigint unsigned,
    `page_id` bigint unsigned NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `country` varchar(100),
    `city` varchar(100),
    `device_type` enum('desktop','mobile','tablet') DEFAULT 'desktop',
    `browser` varchar(100),
    `os` varchar(100),
    `referer` varchar(500),
    `clicked_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_stats_page` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `{prefix}ai_settings` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `provider` enum('openai','claude','zhipu','custom') DEFAULT 'openai',
    `api_key` varchar(500) NOT NULL,
    `api_endpoint` varchar(500),
    `model` varchar(100) DEFAULT 'gpt-3.5-turbo',
    `is_active` tinyint(1) DEFAULT 1,
    `daily_limit` int unsigned DEFAULT 100,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `{prefix}ai_usage_logs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `prompt_tokens` int unsigned DEFAULT 0,
    `completion_tokens` int unsigned DEFAULT 0,
    `total_tokens` int unsigned DEFAULT 0,
    `cost` decimal(10,6) DEFAULT 0,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_ai_usage_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `{prefix}install_config` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `install_key` varchar(64) NOT NULL,
    `site_name` varchar(255) DEFAULT 'LinkHub',
    `site_url` varchar(255) NOT NULL,
    `admin_email` varchar(255) NOT NULL,
    `admin_password` varchar(255) NOT NULL,
    `db_host` varchar(255) NOT NULL,
    `db_port` int unsigned DEFAULT 3306,
    `db_name` varchar(255) NOT NULL,
    `db_user` varchar(255) NOT NULL,
    `db_password` varchar(255) NOT NULL,
    `db_prefix` varchar(50) DEFAULT 'lh_',
    `is_installed` tinyint(1) DEFAULT 0,
    `installed_at` timestamp NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_install_key` (`install_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
