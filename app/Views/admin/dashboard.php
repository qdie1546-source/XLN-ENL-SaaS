<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理后台 - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header - Sticky -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="px-4 md:px-6 py-4">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center gap-4">
                    <a href="/" class="flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        <span class="ml-3 text-xl font-semibold tracking-tight text-gray-900"><?php echo htmlspecialchars(\ConfigHelper::siteName()); ?> 管理后台</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="搜索..." class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] transition-all w-64">
                    </div>
                    <a href="/dashboard" class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors">返回用户后台</a>
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center text-blue-700 font-semibold text-sm">
                        <?php echo substr(htmlspecialchars($_SESSION['user']['name'] ?? 'A'), 0, 1); ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content - Holy Grail Layout -->
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Sidebar -->
            <aside class="lg:col-span-3">
                <?php include __DIR__ . '/partials/sidebar.php'; ?>
            </aside>

            <!-- Main Content Area -->
            <main class="lg:col-span-6 space-y-6">
                <!-- Flash Messages -->
                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 text-sm">
                        <?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-red-700 text-sm">
                        <?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-gray-500 text-sm">总用户数</span>
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                        </div>
                        <div class="text-2xl md:text-3xl font-semibold tracking-tight text-gray-900"><?php echo number_format($totalUsers ?? 0); ?></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-gray-500 text-sm">总页面数</span>
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                        </div>
                        <div class="text-2xl md:text-3xl font-semibold tracking-tight text-gray-900"><?php echo number_format($totalPages ?? 0); ?></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-gray-500 text-sm">总链接数</span>
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                            </div>
                        </div>
                        <div class="text-2xl md:text-3xl font-semibold tracking-tight text-gray-900"><?php echo number_format($totalLinks ?? 0); ?></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-gray-500 text-sm">总浏览数</span>
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                        </div>
                        <div class="text-2xl md:text-3xl font-semibold tracking-tight text-gray-900"><?php echo number_format($totalViews ?? 0); ?></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-gray-500 text-sm">总点击</span>
                            <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center text-cyan-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                        </div>
                        <div class="text-2xl md:text-3xl font-semibold tracking-tight text-gray-900"><?php echo number_format($totalClicks ?? 0); ?></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-gray-500 text-sm">企业用户</span>
                            <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center text-pink-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        </div>
                        <div class="text-2xl md:text-3xl font-semibold tracking-tight text-gray-900"><?php echo number_format($enterpriseCount ?? 0); ?></div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                    <h2 class="font-semibold tracking-tight text-base md:text-lg text-gray-900 mb-4">最近用户</h2>
                    <?php if (empty($recentUsers ?? [])): ?>
                        <p class="text-gray-500 text-sm">暂无用户</p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($recentUsers as $user): ?>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600 font-semibold text-sm">
                                            <?php echo substr(htmlspecialchars($user['name']), 0, 1); ?>
                                        </div>
                                        <div>
                                            <div class="font-medium text-sm text-gray-900"><?php echo htmlspecialchars($user['name']); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        <?php echo date('Y-m-d', strtotime($user['created_at'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </main>

            <!-- Right Sidebar -->
            <aside class="lg:col-span-3 space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                    <h3 class="font-semibold tracking-tight text-base md:text-lg text-gray-900 mb-4">快捷操作</h3>
                    <div class="space-y-2">
                        <a href="/register" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors bg-blue-50 text-blue-700 hover:bg-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            添加用户
                        </a>
                        <a href="/pages/create" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                            查看主题
                        </a>
                        <a href="/admin/config" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            系统设置
                        </a>
                    </div>
                </div>

                <!-- Recent Pages -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                    <h3 class="font-semibold tracking-tight text-base md:text-lg text-gray-900 mb-4">最近页面</h3>
                    <?php if (empty($recentPages ?? [])): ?>
                        <p class="text-gray-500 text-sm">暂无页面</p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($recentPages as $page): ?>
                                <a href="/<?php echo $page['slug']; ?>" target="_blank" class="block py-2 border-b border-gray-100 last:border-0 hover:text-blue-600 transition-colors">
                                    <div class="font-medium text-sm text-gray-900"><?php echo htmlspecialchars($page['title']); ?></div>
                                    <div class="text-xs text-gray-400">/<?php echo htmlspecialchars($page['slug']); ?></div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- System Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                    <h3 class="font-semibold tracking-tight text-base md:text-lg text-gray-900 mb-4">系统信息</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">PHP 版本</span>
                            <span class="text-gray-700"><?php echo phpversion(); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">数据库</span>
                            <span class="text-gray-700">MySQL</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">环境</span>
                            <span class="text-gray-700">开发</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-6">
            <div class="text-center text-sm text-gray-500">
                © 2026 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
