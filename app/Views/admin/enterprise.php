<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>企业管理 - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
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
                    <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900">企业管理</h1>
                </div>

                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
                <?php endif; ?>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <?php if (!empty($enterprises)): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 text-left">
                                        <th class="py-3 text-gray-500 font-medium">公司名称</th>
                                        <th class="py-3 text-gray-500 font-medium">用户邮箱</th>
                                        <th class="py-3 text-gray-500 font-medium">自定义域名</th>
                                        <th class="py-3 text-gray-500 font-medium">到期时间</th>
                                        <th class="py-3 text-gray-500 font-medium">状态</th>
                                        <th class="py-3 text-gray-500 font-medium">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($enterprises as $ep): ?>
                                        <tr class="border-b border-gray-50">
                                            <td class="py-3 text-gray-900 font-medium"><?php echo htmlspecialchars($ep['company_name'] ?: '未设置'); ?></td>
                                            <td class="py-3 text-gray-600"><?php echo htmlspecialchars($ep['email'] ?? ''); ?></td>
                                            <td class="py-3 text-gray-600"><?php echo htmlspecialchars($ep['custom_domain'] ?: '未配置'); ?></td>
                                            <td class="py-3">
                                                <form method="POST" action="/admin/enterprise/<?php echo $ep['id']; ?>/expiry" class="flex items-center gap-2">
                                                    <input type="date" name="expires_at" value="<?php echo htmlspecialchars($ep['expires_at'] ?? ''); ?>" class="w-32 text-xs bg-gray-50 border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                                    <button type="submit" class="text-xs text-blue-600 hover:underline">保存</button>
                                                </form>
                                            </td>
                                            <td class="py-3">
                                                <span class="px-2 py-0.5 text-xs rounded-full <?php echo ($ep['is_active'] ?? 1) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                                    <?php echo ($ep['is_active'] ?? 1) ? '已激活' : '已停用'; ?>
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <div class="flex items-center gap-2">
                                                    <a href="/admin/enterprise/<?php echo $ep['id']; ?>/toggle" class="text-xs text-blue-600 hover:underline"><?php echo ($ep['is_active'] ?? 1) ? '停用' : '启用'; ?></a>
                                                    <form method="POST" action="/admin/enterprise/<?php echo $ep['id']; ?>/delete" onsubmit="return confirm('确定要删除该企业吗？用户将恢复为个人版。')" class="inline">
                                                        <button type="submit" class="text-xs text-red-600 hover:underline">删除</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center py-8">暂无企业用户</p>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-6"><div class="text-center text-sm text-gray-500">&copy; 2026 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>. All rights reserved.</div></div>
    </footer>
</body>
</html>
