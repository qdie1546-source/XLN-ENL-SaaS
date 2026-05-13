<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统设置 - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght400;500;600;700&display=swap" rel="stylesheet">
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
            <main class="lg:col-span-9 space-y-6">
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

                <!-- Page Header -->
                <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900">系统设置</h1>

                <!-- Settings Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 space-y-6">
                    <!-- General Settings -->
                <form method="POST" action="/admin/settings/update">
                    <div>
                        <h2 class="text-lg md:text-xl font-semibold tracking-tight text-gray-900 mb-4 pb-4 border-b border-gray-100">常规设置</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">站点名称</label>
                                <input type="text" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? \ConfigHelper::siteName()); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">站点描述</label>
                                <textarea name="site_description" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] transition-all resize-none"><?php echo htmlspecialchars($settings['site_description'] ?? '社交链接聚合平台'); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Settings -->
                    <div>
                        <h2 class="text-lg md:text-xl font-semibold tracking-tight text-gray-900 mb-4 pb-4 border-b border-gray-100">注册设置</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="allow_registration" <?php echo ($settings['allow_registration'] ?? '1') === '1' ? 'checked' : ''; ?> class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">允许用户注册</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                            保存设置
                        </button>
                    </div>
                </form>
                </div>
            </main>
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
