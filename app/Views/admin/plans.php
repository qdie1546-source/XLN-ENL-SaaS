<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>套餐管理 - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
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
                    <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900">套餐管理</h1>
                </div>

                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
                <?php endif; ?>

                <!-- Create Plan Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">创建套餐</h3>
                    <form method="POST" action="/admin/plans" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">名称</label>
                            <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">标识 (slug)</label>
                            <input type="text" name="slug" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">价格</label>
                            <input type="number" name="price" step="0.01" min="0" value="0" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">类型</label>
                            <select name="type" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                <option value="enterprise">企业版</option>
                                <option value="pages">页面扩容</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">页面上限</label>
                            <input type="number" name="page_limit" min="0" value="0" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">有效天数</label>
                            <input type="number" name="duration_days" min="1" value="365" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">描述</label>
                            <textarea name="description" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 resize-none"></textarea>
                        </div>
                        <div class="md:col-span-3">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium text-sm transition-colors">创建套餐</button>
                        </div>
                    </form>
                </div>

                <!-- Plan List -->
                <?php if (!empty($plans)): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">套餐列表</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 text-left">
                                        <th class="py-3 text-gray-500 font-medium">名称</th>
                                        <th class="py-3 text-gray-500 font-medium">标识</th>
                                        <th class="py-3 text-gray-500 font-medium">类型</th>
                                        <th class="py-3 text-gray-500 font-medium">价格</th>
                                        <th class="py-3 text-gray-500 font-medium">页面上限</th>
                                        <th class="py-3 text-gray-500 font-medium">天数</th>
                                        <th class="py-3 text-gray-500 font-medium">状态</th>
                                        <th class="py-3 text-gray-500 font-medium">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plans as $plan): ?>
                                        <tr class="border-b border-gray-50">
                                            <td class="py-3 text-gray-900 font-medium"><?php echo htmlspecialchars($plan['name']); ?></td>
                                            <td class="py-3 text-gray-500 font-mono text-xs"><?php echo htmlspecialchars($plan['slug']); ?></td>
                                            <td class="py-3">
                                                <span class="px-2 py-0.5 text-xs rounded-full <?php echo $plan['type'] === 'enterprise' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'; ?>">
                                                    <?php echo $plan['type'] === 'enterprise' ? '企业版' : '页面扩容'; ?>
                                                </span>
                                            </td>
                                            <td class="py-3 text-gray-600">&yen;<?php echo number_format($plan['price'] ?? 0, 2); ?></td>
                                            <td class="py-3 text-gray-600"><?php echo $plan['page_limit'] > 0 ? $plan['page_limit'] : '不限'; ?></td>
                                            <td class="py-3 text-gray-600"><?php echo $plan['duration_days']; ?>天</td>
                                            <td class="py-3">
                                                <span class="px-2 py-0.5 text-xs rounded-full <?php echo ($plan['is_active'] ?? 1) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'; ?>">
                                                    <?php echo ($plan['is_active'] ?? 1) ? '上架' : '下架'; ?>
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <a href="/admin/plans/<?php echo $plan['id']; ?>/toggle" class="text-xs text-blue-600 hover:underline"><?php echo ($plan['is_active'] ?? 1) ? '下架' : '上架'; ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                        <p class="text-gray-500 text-sm">暂无套餐</p>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-6"><div class="text-center text-sm text-gray-500">&copy; 2026 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>. All rights reserved.</div></div>
    </footer>
</body>
</html>
