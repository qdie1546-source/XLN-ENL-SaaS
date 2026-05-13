<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查看内容 - 主题市场管理</title>
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
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="/admin/theme-market" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                        <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900">查看内容</h1>
                        <span class="text-sm text-gray-500"><?php echo htmlspecialchars($theme['name']); ?></span>
                    </div>
                    <a href="/admin/theme-market/<?php echo $theme['id']; ?>/edit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm">编辑</a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <div class="flex flex-wrap gap-3 text-sm text-gray-500">
                        <span>作者: <strong class="text-gray-700"><?php echo htmlspecialchars($theme['author_name'] ?? '未知'); ?></strong></span>
                        <span>|</span>
                        <span>状态: <strong class="text-gray-700"><?php echo htmlspecialchars($theme['status'] ?? ''); ?></strong></span>
                        <span>|</span>
                        <span>上架: <strong class="text-gray-700"><?php echo ($theme['is_active'] ?? 0) ? '是' : '否'; ?></strong></span>
                        <span>|</span>
                        <span>价格: <strong class="text-gray-700">&yen;<?php echo number_format($theme['price'] ?? 0, 2); ?></strong></span>
                        <span>|</span>
                        <span>slug: <strong class="text-gray-700"><?php echo htmlspecialchars($theme['slug'] ?? ''); ?></strong></span>
                    </div>
                    <?php if (!empty($theme['description'])): ?>
                    <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                        <?php echo htmlspecialchars($theme['description']); ?>
                    </div>
                    <?php endif; ?>
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">CSS 内容</h3>
                        <pre class="bg-gray-900 text-gray-100 rounded-xl p-6 overflow-auto text-sm font-mono max-h-[600px]"><?php echo htmlspecialchars($theme['css_content'] ?? ''); ?></pre>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-6"><div class="text-center text-sm text-gray-500">&copy; 2026 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>. All rights reserved.</div></div>
    </footer>
</body>
</html>
