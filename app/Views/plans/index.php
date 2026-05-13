<?php
$title = '套餐中心';
$section = 'plans';
require BASE_PATH . '/app/Views/layouts/header.php';
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">套餐中心</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">选择适合您的套餐方案</p>
    </div>
    <?php if (!empty($wallet)): ?>
        <div class="px-4 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm">
            <span class="text-green-600 dark:text-green-400">余额: &yen;<?php echo number_format($wallet['balance'] ?? 0, 2); ?></span>
        </div>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
<?php endif; ?>

<?php if (!empty($plans)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <?php foreach ($plans as $plan): ?>
            <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars($plan['name']); ?></h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo htmlspecialchars($plan['description'] ?: '无描述'); ?></p>
                </div>
                <div class="mb-4">
                    <span class="text-3xl font-bold text-gray-900 dark:text-gray-100">&yen;<?php echo number_format($plan['price'] ?? 0, 2); ?></span>
                    <?php if ($plan['duration_days'] > 0): ?>
                        <span class="text-sm text-gray-500 dark:text-gray-400"> / <?php echo $plan['duration_days']; ?>天</span>
                    <?php endif; ?>
                </div>
                <div class="space-y-2 mb-6 flex-1">
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-gray-600 dark:text-gray-400"><?php echo $plan['type'] === 'enterprise' ? '企业版功能' : '页面扩容'; ?></span>
                    </div>
                    <?php if ($plan['page_limit'] > 0): ?>
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-gray-600 dark:text-gray-400">最多 <?php echo $plan['page_limit']; ?> 个页面</span>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($plan['price'] > 0): ?>
                    <a href="/checkout?type=plan&id=<?php echo $plan['id']; ?>" class="block w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm shadow-sm transition-colors text-center">立即购买</a>
                <?php else: ?>
                    <form method="POST" action="/plans/<?php echo $plan['id']; ?>/purchase">
                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm shadow-sm transition-colors">免费获取</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-12 text-center">
        <p class="text-gray-500 dark:text-gray-400 text-sm">暂无可购买的套餐</p>
    </div>
<?php endif; ?>

<?php if (!empty($myPurchases)): ?>
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">我的购买记录</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800 text-left">
                        <th class="py-3 text-gray-500 font-medium">套餐</th>
                        <th class="py-3 text-gray-500 font-medium">价格</th>
                        <th class="py-3 text-gray-500 font-medium">到期时间</th>
                        <th class="py-3 text-gray-500 font-medium">购买时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($myPurchases as $p): ?>
                        <tr class="border-b border-gray-50 dark:border-gray-800">
                            <td class="py-3 text-gray-900 dark:text-gray-100 font-medium"><?php echo htmlspecialchars($p['plan_name']); ?></td>
                            <td class="py-3 text-gray-600 dark:text-gray-400">&yen;<?php echo number_format($p['price_paid'] ?? 0, 2); ?></td>
                            <td class="py-3 text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($p['expires_at'] ?? '永久'); ?></td>
                            <td class="py-3 text-gray-500 dark:text-gray-500"><?php echo $p['created_at'] ?? ''; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
