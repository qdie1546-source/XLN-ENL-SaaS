<?php require BASE_PATH . '/app/Views/layouts/header.php'; ?>

<?php
$currentUser = \App\Libraries\Session::get('user');
?>
<div class="max-w-md mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">切换账号</h2>

        <div class="space-y-3 mb-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">当前登录</p>
            <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-700">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center text-blue-700 dark:text-blue-300 font-semibold">
                    <?php echo mb_substr(htmlspecialchars($currentUser['name'] ?? '?'), 0, 1); ?>
                </div>
                <div>
                    <div class="font-medium text-sm text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars($currentUser['name'] ?? ''); ?></div>
                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($currentUser['email'] ?? ''); ?></div>
                </div>
                <span class="ml-auto text-xs text-blue-600 font-medium">当前</span>
            </div>
        </div>

        <!-- Login as another account -->
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">登录其他账号</p>
        <form method="POST" action="/login" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">邮箱</label>
                <input type="email" name="email" required class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">密码</label>
                <input type="password" name="password" required class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors">登录并切换</button>
        </form>

        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
            <a href="/register" class="text-sm text-blue-600 hover:text-blue-700 transition-colors">注册新账号</a>
        </div>
    </div>
</div>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
