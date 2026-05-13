<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>页面管理 - 管理后台</title>
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
                <div class="flex items-center gap-4">
                    <a href="/" class="flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </div>
                        <span class="ml-3 text-xl font-semibold tracking-tight text-gray-900"><?php echo htmlspecialchars(\ConfigHelper::siteName()); ?> 管理后台</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/dashboard" class="text-gray-600 hover:text-gray-900 text-sm font-medium">返回用户后台</a>
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center text-blue-700 font-semibold text-sm"><?php echo substr(htmlspecialchars($_SESSION['user']['name'] ?? 'A'), 0, 1); ?></div>
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
                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
                <?php endif; ?>

                <div class="flex items-center justify-between">
                    <h1 class="text-xl md:text-2xl font-semibold text-gray-900">页面管理</h1>
                    <span class="text-sm text-gray-500">共 <?php echo count($pages ?? []); ?> 个页面</span>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <?php if (empty($pages)): ?>
                        <div class="p-8 text-center text-gray-500 text-sm">暂无页面</div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">页面</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">所有者</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">链接数</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">状态</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">创建时间</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">操作</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($pages as $page): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div>
                                                <span class="font-medium text-sm text-gray-900"><?php echo htmlspecialchars($page['title']); ?></span>
                                                <span class="text-xs text-gray-400 ml-1">/<?php echo htmlspecialchars($page['slug']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo htmlspecialchars($page['owner_name'] ?? '未知'); ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo $page['link_count'] ?? 0; ?></td>
                                        <td class="px-4 py-3">
                                            <?php if ($page['is_active'] ?? 1): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">公开</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">隐藏</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500"><?php echo date('Y-m-d', strtotime($page['created_at'] ?? 'now')); ?></td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="/<?php echo htmlspecialchars($page['slug']); ?>" target="_blank" class="text-gray-600 hover:text-gray-900 text-xs font-medium">查看</a>
                                                <a href="/admin/pages/<?php echo $page['id']; ?>/toggle" class="text-blue-600 hover:text-blue-700 text-xs font-medium"><?php echo ($page['is_active'] ?? 1) ? '隐藏' : '公开'; ?></a>
                                                <form method="POST" action="/admin/pages/<?php echo $page['id']; ?>/delete" onsubmit="return confirm('确定删除页面「<?php echo htmlspecialchars($page['title']); ?>」吗？');" style="display:inline">
                                                    <button type="submit" class="text-red-600 hover:text-red-700 text-xs font-medium">删除</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-6 text-center text-sm text-gray-500">&copy; 2026 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>.</div>
    </footer>
</body>
</html>
