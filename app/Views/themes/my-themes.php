<?php require BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">我的主题</h1>
    <a href="/themes/market" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 text-sm font-medium transition-colors">
        返回市场
    </a>
</div>

<!-- Uploaded Themes -->
<section class="mb-8">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">我上传的主题</h2>
    <?php if (empty($myThemes ?? [])): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">还没有上传过主题</p>
            <a href="/themes/upload" class="inline-block mt-2 text-blue-600 hover:text-blue-700 text-sm font-medium">上传第一个主题</a>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($myThemes as $theme): ?>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center shrink-0">
                        <span class="font-bold text-gray-400 dark:text-gray-500"><?php echo mb_substr(htmlspecialchars($theme['name']), 0, 1); ?></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-sm text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars($theme['name']); ?></h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo htmlspecialchars($theme['description'] ?: '无描述'); ?></p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full <?php
                        echo $theme['status'] === 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400' :
                            ($theme['status'] === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400' :
                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400');
                    ?>">
                        <?php echo $theme['status'] === 'approved' ? '已通过' : ($theme['status'] === 'rejected' ? '已拒绝' : '审核中'); ?>
                    </span>
                    <?php if ($theme['status'] === 'approved'): ?>
                        <form method="POST" action="/themes/<?php echo $theme['id']; ?>/apply">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">应用</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- Purchased Themes -->
<section>
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">已获取的主题</h2>
    <?php if (empty($purchasedThemes ?? [])): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">还没有获取过主题</p>
            <a href="/themes/market" class="inline-block mt-2 text-blue-600 hover:text-blue-700 text-sm font-medium">浏览市场</a>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($purchasedThemes as $theme): ?>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center shrink-0">
                        <span class="font-bold text-gray-400 dark:text-gray-500"><?php echo mb_substr(htmlspecialchars($theme['name']), 0, 1); ?></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-sm text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars($theme['name']); ?></h3>
                        <p class="text-xs text-gray-400">获取于 <?php echo date('Y-m-d', strtotime($theme['purchased_at'] ?? '')); ?></p>
                    </div>
                    <form method="POST" action="/themes/<?php echo $theme['id']; ?>/apply">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">应用</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
