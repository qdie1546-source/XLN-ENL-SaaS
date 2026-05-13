<?php
$title = '我的页面';
$section = 'pages';
extract(['user' => $user, 'pages' => $pages]);
require BASE_PATH . '/app/Views/layouts/header.php';
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">我的页面</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">管理您的 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?> 页面</p>
    </div>
    <a href="/pages/create" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-medium shadow-sm transition-colors text-sm">
        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        创建页面
    </a>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
<?php endif; ?>

<?php if (empty($pages)): ?>
    <div class="text-center py-20 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">还没有页面</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">创建您的第一个 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?> 页面，开始分享链接</p>
        <a href="/pages/create" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm transition-colors">创建第一个页面</a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($pages as $page): ?>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars($page['title']); ?></h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">/<?php echo htmlspecialchars($page['slug']); ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400 mb-4">
                    <span><?php echo $page['view_count']; ?> 访问</span>
                    <span><?php echo $page['click_count']; ?> 点击</span>
                </div>
                <div class="flex gap-2">
                    <a href="/<?php echo $page['slug']; ?>" target="_blank" class="flex-1 text-center px-3 py-2 text-xs font-medium bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">查看</a>
                    <a href="/pages/<?php echo $page['id']; ?>/edit" class="flex-1 text-center px-3 py-2 text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">编辑</a>
                    <form method="POST" action="/pages/<?php echo $page['id']; ?>/delete" onsubmit="return confirm('确定删除？')" class="flex-1">
                        <button type="submit" class="w-full px-3 py-2 text-xs font-medium bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">删除</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
