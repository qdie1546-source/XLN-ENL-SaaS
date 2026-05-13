<?php
$title = '数据统计';
$section = 'enterprise';
require BASE_PATH . '/app/Views/layouts/header.php';

$db = \App\Libraries\Database::getInstance();
$prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
$userId = $authUser['id'] ?? 0;

$myPages = $db->fetch("SELECT COUNT(*) as count FROM `{$prefix}pages` WHERE `user_id` = ?", [$userId])['count'] ?? 0;
$myLinks = $db->fetch("SELECT COUNT(*) as count FROM `{$prefix}links` WHERE `user_id` = ?", [$userId])['count'] ?? 0;
$myViews = $db->fetch("SELECT COUNT(*) as count FROM `{$prefix}statistics` WHERE `page_id` IN (SELECT `id` FROM `{$prefix}pages` WHERE `user_id` = ?)", [$userId])['count'] ?? 0;
$myClicks = $db->fetch("SELECT COUNT(*) as count FROM `{$prefix}statistics` WHERE `link_id` IS NOT NULL AND `page_id` IN (SELECT `id` FROM `{$prefix}pages` WHERE `user_id` = ?)", [$userId])['count'] ?? 0;
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars($profile['company_name'] ?: '企业数据统计'); ?></h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">企业页面和链接数据概览</p>
    </div>
    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-full">企业版</span>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-gray-500 dark:text-gray-400 text-sm">我的页面</span>
            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>
        <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100"><?php echo number_format($myPages); ?></div>
    </div>
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-gray-500 dark:text-gray-400 text-sm">我的链接</span>
            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center text-purple-600 dark:text-purple-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </div>
        </div>
        <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100"><?php echo number_format($myLinks); ?></div>
    </div>
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-gray-500 dark:text-gray-400 text-sm">总访问量</span>
            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center text-green-600 dark:text-green-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
        </div>
        <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100"><?php echo number_format($myViews); ?></div>
    </div>
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-gray-500 dark:text-gray-400 text-sm">链接点击</span>
            <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center text-amber-600 dark:text-amber-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
        <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100"><?php echo number_format($myClicks); ?></div>
    </div>
</div>

<!-- Company Overview -->
<div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">企业概况</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-500 dark:text-gray-400">公司名称</span>
            <span class="text-gray-900 dark:text-gray-100 font-medium"><?php echo htmlspecialchars($profile['company_name'] ?: '未设置'); ?></span>
        </div>
        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-500 dark:text-gray-400">行业</span>
            <span class="text-gray-900 dark:text-gray-100 font-medium"><?php echo htmlspecialchars($profile['industry'] ?: '未设置'); ?></span>
        </div>
        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-500 dark:text-gray-400">公司规模</span>
            <span class="text-gray-900 dark:text-gray-100 font-medium"><?php echo htmlspecialchars($profile['company_size'] ?: '未设置'); ?></span>
        </div>
        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-500 dark:text-gray-400">自定义域名</span>
            <span class="text-gray-900 dark:text-gray-100 font-medium"><?php echo htmlspecialchars($profile['custom_domain'] ?: '未配置'); ?></span>
        </div>
    </div>
</div>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
