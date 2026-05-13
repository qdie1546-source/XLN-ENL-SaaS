<?php require BASE_PATH . '/app/Views/layouts/header.php'; ?>

<?php
$user = $user ?? \App\Libraries\Session::get('user');
$userName = htmlspecialchars($user['name'] ?? '');
$avatarUrl = $user['avatar_url'] ?? null;
?>

<div class="max-w-2xl mx-auto space-y-6">
    <!-- Profile Settings -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">编辑资料</h2>

        <!-- Avatar Upload -->
        <div class="mb-6 flex items-center gap-4">
            <?php if ($avatarUrl): ?>
                <img src="<?php echo htmlspecialchars($avatarUrl); ?>" alt="<?php echo $userName; ?>" class="w-16 h-16 rounded-xl object-cover">
            <?php else: ?>
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center text-blue-700 dark:text-blue-300 font-semibold text-2xl">
                    <?php echo mb_substr($userName ?: '?', 0, 1); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="/profile/avatar" enctype="multipart/form-data" class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">头像</label>
                <div class="flex items-center gap-2">
                    <input type="file" name="avatar" accept="image/*" class="text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/50 dark:file:text-blue-300">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">上传</button>
                </div>
                <p class="text-xs text-gray-500 mt-1">支持 JPG、PNG、GIF，最大 2MB</p>
            </form>
        </div>

        <!-- Profile Form -->
        <form method="POST" action="/profile/update" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">昵称</label>
                <input type="text" name="name" value="<?php echo $userName; ?>" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">手机号</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">个人简介</label>
                <textarea name="bio" rows="3" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors">保存更改</button>
        </form>
    </div>

    <!-- Password Change -->
    <div id="password" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">修改密码</h2>
        <form method="POST" action="/profile/password" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">当前密码</label>
                <input type="password" name="current_password" required class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">新密码</label>
                <input type="password" name="new_password" required minlength="6" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">确认新密码</label>
                <input type="password" name="confirm_password" required minlength="6" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors">修改密码</button>
        </form>
    </div>
</div>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
