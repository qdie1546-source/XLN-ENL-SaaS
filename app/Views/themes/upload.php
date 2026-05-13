<?php require BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="/themes/market" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">上传主题</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="/themes/upload" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">主题名称</label>
                <input type="text" name="name" required class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="例如：深色科技风">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">描述</label>
                <textarea name="description" rows="2" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none" placeholder="描述你的主题风格..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    价格 (¥)
                    <span class="text-xs text-gray-500 font-normal ml-2">设定 0 则为免费，平台抽取佣金</span>
                </label>
                <input type="number" name="price" step="0.01" min="0" value="0" class="w-full max-w-xs bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="0.00">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">标签</label>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $tagModel = new \LinkHub\Models\Tag();
                    $allTags = $tagModel->all();
                    foreach ($allTags as $tag):
                    ?>
                    <label class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <input type="checkbox" name="tag_ids[]" value="<?php echo $tag['id']; ?>" class="text-blue-600 rounded">
                        <?php echo htmlspecialchars($tag['name']); ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    CSS 内容
                    <span class="text-xs text-gray-500 font-normal ml-2">自定义 CSS，将应用到 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?> 页面</span>
                </label>
                <textarea name="css_content" required rows="12" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none" placeholder="/* 你的自定义 CSS */
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.link-card {
    border-radius: 16px;
    backdrop-filter: blur(10px);
}"></textarea>
            </div>

            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                    提交审核
                </button>
                <a href="/themes" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 text-sm font-medium transition-colors">取消</a>
            </div>
        </form>

        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <p class="text-xs text-blue-700 dark:text-blue-300">
                <strong>提示：</strong>提交后主题将进入审核流程。管理员审核通过后，你可以在主题市场中设定价格。
            </p>
        </div>
    </div>
</div>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
