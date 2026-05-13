<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>主题管理 - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="px-4 md:px-6 py-4">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <a href="/" class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center"><svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg></div>
                    <span class="ml-3 text-xl font-semibold tracking-tight text-gray-900"><?php echo htmlspecialchars(\ConfigHelper::siteName()); ?> 管理后台</span>
                </a>
                <div class="flex items-center gap-4">
                    <a href="/dashboard" class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors">返回用户后台</a>
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center text-blue-700 font-semibold text-sm"><?php echo mb_substr(htmlspecialchars($_SESSION['user']['name'] ?? 'A'), 0, 1); ?></div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 md:px-6 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <aside class="lg:col-span-3">
                <?php include __DIR__ . '/partials/sidebar.php'; ?>
            </aside>

            <main class="lg:col-span-9 space-y-6">
                <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900">主题管理</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($themes as $theme): ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                            <div class="w-24 h-24 rounded-xl bg-gradient-to-br <?php
                                $slug = $theme['slug'] ?? '';
                                $gradients = ['minimal' => 'from-slate-50 to-slate-200', 'gradient' => 'from-purple-500 via-pink-500 to-orange-400', 'card' => 'from-blue-100 to-indigo-200', 'dark' => 'from-gray-800 to-gray-950', 'vintage' => 'from-amber-100 to-orange-200'];
                                echo $gradients[$slug] ?? 'from-slate-100 to-slate-200';
                            ?> flex items-center justify-center shrink-0 shadow-sm">
                                <span class="text-2xl font-bold text-gray-800/50"><?php echo mb_substr($theme['name'], 0, 1); ?></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($theme['name']); ?></h3>
                                <p class="text-xs text-gray-500 mt-1">系统主题</p>
                                <p class="text-xs text-gray-400 mt-1 font-mono">slug: <?php echo htmlspecialchars($slug); ?></p>
                                <a href="/admin/themes/<?php echo $theme['id']; ?>/edit" class="inline-block mt-2 text-xs px-3 py-1 rounded-lg font-medium bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">编辑</a>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full <?php echo $theme['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'; ?>"><?php echo $theme['is_active'] ? '已启用' : '已禁用'; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-6"><div class="text-center text-sm text-gray-500">&copy; 2026 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>. All rights reserved.</div></div>
    </footer>
</body>
</html>
