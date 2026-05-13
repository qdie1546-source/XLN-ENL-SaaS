<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>标签管理 - 管理后台</title>
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
                    <h1 class="text-xl md:text-2xl font-semibold text-gray-900">标签管理</h1>
                </div>

                <!-- Add Tag -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">添加标签</h3>
                    <form method="POST" action="/admin/tags" class="flex gap-3">
                        <input type="text" name="name" placeholder="输入标签名称" required
                               class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm transition-colors">添加</button>
                    </form>
                </div>

                <!-- Tags List -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <?php if (empty($tags)): ?>
                        <div class="p-8 text-center text-gray-500 text-sm">暂无标签</div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">名称</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">标识</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">创建时间</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">操作</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($tags as $tag): ?>
                                    <tr class="hover:bg-gray-50" id="tag-row-<?php echo $tag['id']; ?>">
                                        <td class="px-4 py-3">
                                            <span class="tag-display inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><?php echo htmlspecialchars($tag['name']); ?></span>
                                            <form method="POST" action="/admin/tags/<?php echo $tag['id']; ?>" class="tag-edit-form hidden flex items-center gap-2">
                                                <input type="text" name="name" value="<?php echo htmlspecialchars($tag['name']); ?>" class="px-2 py-1 border border-gray-300 rounded text-sm w-32 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                                <button type="submit" class="text-green-600 hover:text-green-700 text-sm font-medium">保存</button>
                                                <button type="button" onclick="cancelEdit(<?php echo $tag['id']; ?>)" class="text-gray-500 hover:text-gray-700 text-sm">取消</button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500"><?php echo htmlspecialchars($tag['slug']); ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-500"><?php echo htmlspecialchars($tag['created_at'] ?? ''); ?></td>
                                        <td class="px-4 py-3 text-right">
                                            <button type="button" onclick="editTag(<?php echo $tag['id']; ?>)" class="text-blue-600 hover:text-blue-700 text-sm font-medium mr-3">编辑</button>
                                            <form method="POST" action="/admin/tags/<?php echo $tag['id']; ?>/delete" onsubmit="return confirm('确定删除标签「<?php echo htmlspecialchars($tag['name']); ?>」吗？');" style="display:inline">
                                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">删除</button>
                                            </form>
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
    <script>
    function editTag(id) {
        var row = document.getElementById('tag-row-' + id);
        row.querySelector('.tag-display').classList.add('hidden');
        row.querySelector('.tag-edit-form').classList.remove('hidden');
    }
    function cancelEdit(id) {
        var row = document.getElementById('tag-row-' + id);
        row.querySelector('.tag-display').classList.remove('hidden');
        row.querySelector('.tag-edit-form').classList.add('hidden');
    }
    </script>
</body>
</html>
