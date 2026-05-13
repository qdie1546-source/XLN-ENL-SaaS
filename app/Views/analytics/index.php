<?php
$title = '数据分析';
$section = 'analytics';
require BASE_PATH . '/app/Views/layouts/header.php';
?>

<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">数据分析</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">查看页面访问统计与趋势</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">今日访问</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo number_format($todayViews); ?></p>
    </div>

    <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">总访问量</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo number_format($totalViews); ?></p>
    </div>

    <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">总点击量</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo number_format($totalClicks); ?></p>
    </div>

    <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                <svg class="h-5 w-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">活跃页面</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo $activePages; ?></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Daily Trend Table -->
    <div class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">近7日趋势</h3>
        <?php if (!empty($dailyStats)): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <th class="text-left py-3 text-gray-500 dark:text-gray-400 font-medium">日期</th>
                            <th class="text-right py-3 text-gray-500 dark:text-gray-400 font-medium">访问量</th>
                            <th class="text-right py-3 text-gray-500 dark:text-gray-400 font-medium">独立访客</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dailyStats as $row): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="py-2 text-gray-700 dark:text-gray-300"><?php echo htmlspecialchars($row['date']); ?></td>
                                <td class="py-2 text-right font-medium text-gray-900 dark:text-gray-100"><?php echo number_format($row['total']); ?></td>
                                <td class="py-2 text-right text-gray-700 dark:text-gray-300"><?php echo number_format($row['unique_ips'] ?? 0); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-8">暂无数据</p>
        <?php endif; ?>
    </div>

    <!-- Device Breakdown -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">设备分布</h3>
        <?php if (!empty($deviceBreakdown)): ?>
            <div class="space-y-4">
                <?php foreach ($deviceBreakdown as $device): ?>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($device['device_type'] ?: '未知'); ?></span>
                            <span class="font-medium text-gray-900 dark:text-gray-100"><?php echo number_format($device['total']); ?></span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                            <div class="h-2 rounded-full bg-blue-500" style="width: <?php echo $totalViews > 0 ? round(($device['total'] / $totalViews) * 100) : 0; ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-8">暂无数据</p>
        <?php endif; ?>
    </div>
</div>

<!-- Page Ranking -->
<div class="mt-8 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">页面排名</h3>
    <?php if (!empty($pageRanking)): ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="text-left py-3 text-gray-500 dark:text-gray-400 font-medium w-12">#</th>
                        <th class="text-left py-3 text-gray-500 dark:text-gray-400 font-medium">页面</th>
                        <th class="text-right py-3 text-gray-500 dark:text-gray-400 font-medium">访问量</th>
                        <th class="text-right py-3 text-gray-500 dark:text-gray-400 font-medium">点击量</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; foreach ($pageRanking as $p): ?>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="py-2 text-gray-400 font-medium"><?php echo $rank++; ?></td>
                            <td class="py-2">
                                <a href="/<?php echo htmlspecialchars($p['slug']); ?>" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    <?php echo htmlspecialchars($p['title']); ?>
                                </a>
                            </td>
                            <td class="py-2 text-right text-gray-700 dark:text-gray-300"><?php echo number_format($p['view_count']); ?></td>
                            <td class="py-2 text-right text-gray-700 dark:text-gray-300"><?php echo number_format($p['click_count']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-8">暂无数据</p>
    <?php endif; ?>
</div>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
