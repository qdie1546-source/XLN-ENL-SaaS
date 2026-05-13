<?php require BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">主题市场</h1>
    <div class="flex items-center gap-2">
        <a href="/themes/upload" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors">
            上传主题
        </a>
        <a href="/themes/my" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 text-sm font-medium transition-colors">
            我的主题
        </a>
    </div>
</div>

<?php if (empty($marketThemes ?? [])): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
        <p class="text-gray-500 dark:text-gray-400">主题市场暂无内容</p>
        <a href="/themes/upload" class="inline-block mt-4 text-blue-600 hover:text-blue-700 font-medium text-sm">成为第一个上传者</a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($marketThemes as $theme): ?>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
                <!-- Preview -->
                <div class="h-32 relative bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                    <div class="text-center">
                        <span class="text-3xl font-bold text-gray-400 dark:text-gray-500"><?php echo mb_substr(htmlspecialchars($theme['name']), 0, 2); ?></span>
                    </div>
                    <?php if ($theme['price'] > 0): ?>
                        <span class="absolute top-2 right-2 px-2 py-1 bg-yellow-400 text-yellow-900 text-xs font-bold rounded-full">
                            &yen;<?php echo number_format($theme['price'], 2); ?>
                        </span>
                    <?php else: ?>
                        <span class="absolute top-2 right-2 px-2 py-1 bg-green-500 text-white text-xs font-medium rounded-full">免费</span>
                    <?php endif; ?>
                </div>
                <!-- Info -->
                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars($theme['name']); ?></h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex-1"><?php echo htmlspecialchars($theme['description'] ?: '无描述'); ?></p>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-xs text-gray-400">作者: <?php echo htmlspecialchars($theme['author_name'] ?? '未知'); ?></span>
                        <?php if ($theme['price'] > 0): ?>
                            <a href="/checkout?type=theme&id=<?php echo $theme['id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors inline-block">购买</a>
                        <?php else: ?>
                            <a href="/themes/my" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors inline-block">获取</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
