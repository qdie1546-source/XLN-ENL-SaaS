<?php

return [
    // 首页
    ['GET', '/', 'HomeController@index'],

    // 用户认证
    ['GET', '/login', 'Auth/login'],
    ['POST', '/login', 'Auth/doLogin'],
    ['GET', '/register', 'Auth/register'],
    ['POST', '/register', 'Auth/doRegister'],
    ['POST', '/logout', 'Auth/logout'],
    ['GET', '/logout', 'Auth/logout'],

    // 用户资料 & 设置
    ['GET', '/profile/settings', 'ProfileController@settings'],
    ['POST', '/profile/update', 'ProfileController@update'],
    ['POST', '/profile/password', 'ProfileController@password'],
    ['POST', '/profile/avatar', 'ProfileController@avatar'],
    ['GET', '/profile/switch', 'ProfileController@switchAccount'],
    ['POST', '/profile/switch/{id}', 'ProfileController@doSwitch'],
    ['GET', '/profile/toggle-mode', 'ProfileController@toggleMode'],

    // 用户后台
    ['GET', '/dashboard', 'DashboardController@index'],

    // 页面管理
    ['GET', '/pages', 'PageController@index'],
    ['GET', '/pages/create', 'PageController@create'],
    ['POST', '/pages', 'PageController@store'],
    ['GET', '/pages/{id}/edit', 'PageController@edit'],
    ['POST', '/pages/{id}', 'PageController@update'],
    ['POST', '/pages/{id}/delete', 'PageController@delete'],

    // 链接管理
    ['GET', '/pages/{pageId}/links', 'LinkController@index'],
    ['POST', '/links', 'LinkController@store'],
    ['POST', '/links/{id}', 'LinkController@update'],
    ['POST', '/links/{id}/delete', 'LinkController@delete'],
    ['POST', '/links/reorder', 'LinkController@reorder'],

    // 主题
    ['GET', '/themes', 'ThemeController@index'],
    ['GET', '/themes/preview/{slug}', 'ThemeController@preview'],
    ['GET', '/preview/{slug}', 'PreviewController@theme'],
    ['POST', '/themes/{id}/apply', 'ThemeController@apply'],
    ['POST', '/themes/{slug}/apply-to', 'ThemeController@applyTo'],

    // 主题市场 (用户端) — /themes/market 已合并到 /themes
    ['GET', '/themes/market', 'ThemeMarketController@market'],
    ['GET', '/themes/upload', 'ThemeMarketController@upload'],
    ['POST', '/themes/upload', 'ThemeMarketController@doUpload'],
    ['GET', '/themes/my', 'ThemeMarketController@myThemes'],
    ['POST', '/themes/{id}/purchase', 'ThemeMarketController@purchase'],
    ['POST', '/themes/{id}/apply-market', 'ThemeMarketController@apply'],

    // 数据分析
    ['GET', '/analytics', 'AnalyticsController@index'],

    // AI 功能
    ['GET', '/ai/generate', 'AIController@generate'],
    ['POST', '/ai/generate', 'AIController@doGenerate'],
    ['GET', '/ai/history', 'AIController@history'],

    // 企业功能
    ['GET', '/enterprise', 'EnterpriseController@index'],
    ['GET', '/enterprise/settings', 'EnterpriseController@settings'],
    ['POST', '/enterprise', 'EnterpriseController@update'],
    ['POST', '/enterprise/domain', 'EnterpriseController@domain'],
    ['POST', '/enterprise/activate', 'EnterpriseController@activate'],
    ['GET', '/enterprise/ai-theme', 'EnterpriseController@aiTheme'],
    ['POST', '/enterprise/ai-theme/generate', 'EnterpriseController@generateTheme'],

    // 套餐
    ['GET', '/plans', 'PlanController@index'],
    ['POST', '/plans/{id}/purchase', 'PlanController@purchase'],

    // 管理后台 - 认证
    ['GET', '/admin/login', 'Admin/Auth/login'],
    ['POST', '/admin/login', 'Admin/Auth/doLogin'],
    ['GET', '/admin/logout', 'Admin/Auth/logout'],
    ['POST', '/admin/logout', 'Admin/Auth/logout'],

    // 管理后台 - 页面
    ['GET', '/admin', 'Admin/DashboardController@index'],
    ['GET', '/admin/users', 'Admin/UserController@index'],
    ['GET', '/admin/users/{id}/edit', 'Admin/UserController@edit'],
    ['POST', '/admin/users/{id}', 'Admin/UserController@update'],
    ['POST', '/admin/users/{id}/delete', 'Admin/UserController@delete'],
    ['GET', '/admin/pages', 'Admin/PageController@index'],
    ['GET', '/admin/pages/{id}/toggle', 'Admin/PageController@toggle'],
    ['POST', '/admin/pages/{id}/delete', 'Admin/PageController@delete'],
    ['GET', '/admin/config', 'Admin/ConfigController@index'],
    ['POST', '/admin/config/update', 'Admin/ConfigController@update'],
    ['GET', '/admin/themes', 'Admin/ThemeController@index'],
    ['GET', '/admin/themes/{id}/edit', 'Admin/ThemeController@edit'],
    ['POST', '/admin/themes/{id}', 'Admin/ThemeController@update'],
    ['GET', '/admin/settings', 'Admin/SettingController@index'],
    ['POST', '/admin/settings/update', 'Admin/SettingController@update'],
    // 管理后台 - 企业管理
    ['GET', '/admin/enterprise', 'Admin/EnterpriseController@index'],
    ['GET', '/admin/enterprise/{id}/toggle', 'Admin/EnterpriseController@toggle'],
    ['POST', '/admin/enterprise/{id}/expiry', 'Admin/EnterpriseController@editExpiry'],
    ['POST', '/admin/enterprise/{id}/delete', 'Admin/EnterpriseController@delete'],

    // 管理后台 - 套餐管理
    ['GET', '/admin/plans', 'Admin/PlanController@index'],
    ['POST', '/admin/plans', 'Admin/PlanController@store'],
    ['POST', '/admin/plans/{id}', 'Admin/PlanController@update'],
    ['GET', '/admin/plans/{id}/toggle', 'Admin/PlanController@toggle'],

    // 管理后台 - 主题市场
    ['GET', '/admin/theme-market', 'Admin/ThemeMarketController@index'],
    ['GET', '/admin/theme-market/{id}/approve', 'Admin/ThemeMarketController@approve'],
    ['GET', '/admin/theme-market/{id}/reject', 'Admin/ThemeMarketController@reject'],
    ['GET', '/admin/theme-market/{id}/toggle', 'Admin/ThemeMarketController@toggle'],
    ['GET', '/admin/theme-market/{id}/edit', 'Admin/ThemeMarketController@edit'],
    ['POST', '/admin/theme-market/{id}', 'Admin/ThemeMarketController@update'],
    ['GET', '/admin/theme-market/{id}/content', 'Admin/ThemeMarketController@content'],

    // 管理后台 - 标签管理
    ['GET', '/admin/tags', 'Admin/TagController@index'],
    ['POST', '/admin/tags', 'Admin/TagController@store'],
    ['POST', '/admin/tags/{id}', 'Admin/TagController@update'],
    ['POST', '/admin/tags/{id}/delete', 'Admin/TagController@delete'],

    // 钱包
    ['GET', '/wallet', 'WalletController@index'],
    ['POST', '/wallet/deposit', 'WalletController@deposit'],
    ['POST', '/wallet/withdraw', 'WalletController@withdraw'],

    // 收银台
    ['GET', '/checkout', 'CheckoutController@index'],
    ['POST', '/checkout/process', 'CheckoutController@process'],
    ['POST', '/payment/notify', 'PaymentController@notify'],
    ['GET', '/payment/notify', 'PaymentController@notify'],
    ['GET', '/payment/return', 'PaymentController@return'],

    // 访问追踪
    ['GET', '/track/link/{id}', 'TrackController@link'],

    // 公开页面
    ['GET', '/{slug}', 'PublicController@page'],
    ['GET', '/{slug}/qr', 'PublicController@qr'],
];
