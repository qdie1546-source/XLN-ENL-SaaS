<?php
// User dropdown menu component
$authUser = \App\Libraries\Session::get('user');
$avatarUrl = $authUser['avatar_url'] ?? null;
$userName = htmlspecialchars($authUser['name'] ?? 'User');
$userInitial = mb_substr($userName, 0, 1);
?>
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center gap-2 rounded-lg p-1.5 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800">
        <?php if ($avatarUrl): ?>
            <img src="<?php echo htmlspecialchars($avatarUrl); ?>" alt="<?php echo $userName; ?>" class="w-8 h-8 rounded-lg object-cover">
        <?php else: ?>
            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center text-blue-700 dark:text-blue-300 font-semibold text-sm">
                <?php echo $userInitial; ?>
            </div>
        <?php endif; ?>
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" x-cloak
         class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
            <div class="font-medium text-sm text-gray-900 dark:text-gray-100"><?php echo $userName; ?></div>
            <div class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo htmlspecialchars($authUser['email'] ?? ''); ?></div>
        </div>

        <a href="/profile/settings" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            编辑资料
        </a>

        <a href="/profile/settings#password" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-.743-1.657l2.593-2.593A6 6 0 1119 9z"/>
            </svg>
            修改密码
        </a>

        <a href="/profile/switch" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            切换账号
        </a>

        <div class="border-t border-gray-100 dark:border-gray-700 mt-1 pt-1">
            <a href="/logout" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                退出登录
            </a>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
