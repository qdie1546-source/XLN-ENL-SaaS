<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>主题市场管理 - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
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
                <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900">主题市场管理</h1>

                <!-- Filter Tabs -->
                <div class="flex gap-2 flex-wrap">
                    <?php
                    $tabs = [
                        'all' => '全部',
                        'pending' => '未审核',
                        'approved' => '已上架',
                        'rejected' => '已拒绝',
                        'delisted' => '已下架',
                    ];
                    foreach ($tabs as $key => $label):
                        $active = ($tab ?? 'all') === $key;
                    ?>
                        <a href="/admin/theme-market?tab=<?php echo $key; ?>"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $active ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'; ?>">
                            <?php echo $label; ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
                <?php endif; ?>

                <?php if (empty($themes ?? [])): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                        <p class="text-gray-500 text-sm">暂无用户上传的主题</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($themes as $theme): ?>
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center shrink-0">
                                        <span class="text-xl font-bold text-gray-400"><?php echo mb_substr(htmlspecialchars($theme['name']), 0, 1); ?></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($theme['name']); ?></h3>
                                            <span class="text-xs px-2 py-0.5 rounded-full <?php
                                                echo $theme['status'] === 'approved' ? 'bg-green-100 text-green-700' :
                                                    ($theme['status'] === 'rejected' ? 'bg-red-100 text-red-700' :
                                                    'bg-yellow-100 text-yellow-700');
                                            ?>">
                                                <?php echo $theme['status'] === 'approved' ? '已通过' : ($theme['status'] === 'rejected' ? '已拒绝' : '待审核'); ?>
                                            </span>
                                            <span class="text-xs px-2 py-0.5 rounded-full <?php echo $theme['is_active'] ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500'; ?>">
                                                <?php echo $theme['is_active'] ? '上架' : '下架'; ?>
                                            </span>
                                            <?php if ($theme['price'] > 0): ?>
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700">&yen;<?php echo number_format($theme['price'], 2); ?></span>
                                            <?php else: ?>
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">免费</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo htmlspecialchars($theme['description'] ?: '无描述'); ?></p>
                                        <p class="text-xs text-gray-400 mt-1">作者: <?php echo htmlspecialchars($theme['author_name'] ?? '未知'); ?> | slug: <?php echo htmlspecialchars($theme['slug']); ?></p>

                                        <!-- Admin Actions -->
                                        <div class="flex items-center gap-2 mt-3 flex-wrap">
                                            <a href="/admin/theme-market/<?php echo $theme['id']; ?>/content" class="text-xs px-3 py-1.5 rounded-lg font-medium bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors">查看内容</a>
                                            <a href="/admin/theme-market/<?php echo $theme['id']; ?>/edit" class="text-xs px-3 py-1.5 rounded-lg font-medium bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">编辑</a>
                                            <?php if ($theme['status'] === 'pending'): ?>
                                                <a href="/admin/theme-market/<?php echo $theme['id']; ?>/approve" class="text-xs px-3 py-1.5 rounded-lg font-medium bg-green-50 text-green-600 hover:bg-green-100 transition-colors">审核通过</a>
                                                <a href="/admin/theme-market/<?php echo $theme['id']; ?>/reject" class="text-xs px-3 py-1.5 rounded-lg font-medium bg-red-50 text-red-600 hover:bg-red-100 transition-colors">拒绝</a>
                                            <?php endif; ?>
                                            <?php if ($theme['status'] === 'approved'): ?>
                                                <span class="text-xs text-gray-500">价格: &yen;<?php echo number_format($theme['price'] ?? 0, 2); ?></span>
                                            <?php endif; ?>
                                            <a href="/admin/theme-market/<?php echo $theme['id']; ?>/toggle" class="text-xs px-3 py-1.5 rounded-lg font-medium <?php echo $theme['is_active'] ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100'; ?> transition-colors">
                                                <?php echo $theme['is_active'] ? '下架' : '上架'; ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
