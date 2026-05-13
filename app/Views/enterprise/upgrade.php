<?php
$title = '升级企业版';
$section = 'enterprise';
require BASE_PATH . '/app/Views/layouts/header.php';
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">升级企业版</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">解锁更多企业级功能</p>
    </div>
    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-sm font-medium rounded-full">个人版</span>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
<?php endif; ?>

<!-- Feature Comparison -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
    <!-- Personal -->
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">个人版</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">免费</p>
        <ul class="space-y-3 mb-6">
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                最多 5 个页面
            </li>
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                免费主题
            </li>
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                基础数据分析
            </li>
            <li class="flex items-center gap-2 text-sm text-gray-400 line-through">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                自定义域名
            </li>
            <li class="flex items-center gap-2 text-sm text-gray-400 line-through">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                品牌定制
            </li>
        </ul>
        <div class="py-2.5 text-center text-sm font-medium text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-xl">当前方案</div>
    </div>

    <!-- Enterprise -->
    <div class="rounded-2xl border-2 border-blue-500 bg-white dark:bg-gray-900 shadow-lg p-6 relative lg:scale-105">
        <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full">推荐</span>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 mt-2">企业版</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">&yen;99<span class="text-sm font-normal text-gray-500">/月</span></p>
        <ul class="space-y-3 mb-6">
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                无限页面
            </li>
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                所有主题免费
            </li>
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                高级数据分析
            </li>
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                自定义域名
            </li>
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                品牌定制
            </li>
        </ul>
        <a href="/plans" class="block w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm shadow-sm transition-colors text-center">
            立即开通
        </a>
    </div>

    <!-- Custom -->
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">定制版</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">联系报价</p>
        <ul class="space-y-3 mb-6">
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                所有企业版功能
            </li>
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                专属客户经理
            </li>
            <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                API 对接
            </li>
        </ul>
        <div class="py-2.5 text-center text-sm font-medium text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-xl">联系我们</div>
    </div>
</div>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
