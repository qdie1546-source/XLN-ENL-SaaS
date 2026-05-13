<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑主题 - 主题市场管理</title>
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
                <div class="flex items-center gap-3">
                    <a href="/admin/theme-market" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900">编辑主题</h1>
                    <span class="text-sm text-gray-500"><?php echo htmlspecialchars($theme['name']); ?></span>
                </div>

                <form action="/admin/theme-market/<?php echo $theme['id']; ?>" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">主题名称</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($theme['name']); ?>" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">描述</label>
                        <textarea id="description" name="description" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"><?php echo htmlspecialchars($theme['description'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label for="css_content" class="block text-sm font-medium text-gray-700 mb-2">CSS 内容</label>
                        <textarea id="css_content" name="css_content" rows="16"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm"><?php echo htmlspecialchars($theme['css_content'] ?? ''); ?></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">价格 (&yen;)</label>
                            <input type="number" id="price" name="price" value="<?php echo number_format($theme['price'] ?? 0, 2); ?>" step="0.01" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div class="flex items-end pb-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" <?php echo ($theme['is_active'] ?? 0) ? 'checked' : ''; ?>
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm font-medium text-gray-700">上架</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">标签</label>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($allTags as $tag): ?>
                            <label class="flex items-center gap-1.5 px-3 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>"
                                       <?php echo in_array($tag['id'], array_column($theme['tags'] ?? [], 'id')) ? 'checked' : ''; ?>
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                <?php echo htmlspecialchars($tag['name']); ?>
                            </label>
                            <?php endforeach; ?>
                            <?php if (empty($allTags)): ?>
                            <span class="text-sm text-gray-500">暂无标签</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-gray-500 pt-2">
                        <span>作者: <?php echo htmlspecialchars($theme['author_name'] ?? '未知'); ?></span>
                        <span>状态: <?php echo htmlspecialchars($theme['status'] ?? ''); ?></span>
                        <span>slug: <?php echo htmlspecialchars($theme['slug'] ?? ''); ?></span>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="/admin/theme-market" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium text-sm hover:bg-gray-50">取消</a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm">保存更改</button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-6"><div class="text-center text-sm text-gray-500">&copy; 2026 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>. All rights reserved.</div></div>
    </footer>
</body>
</html>
