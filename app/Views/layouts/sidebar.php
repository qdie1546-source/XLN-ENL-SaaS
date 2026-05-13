<!DOCTYPE html>
<html lang="zh-CN" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' - ' . htmlspecialchars(\ConfigHelper::siteName()) : htmlspecialchars(\ConfigHelper::siteName()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
    <script>
        (function() {
            var saved = localStorage.getItem('linkhub-dark');
            if (saved === 'true' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen">
<div class="flex min-h-screen">

<!-- Sidebar -->
<aside id="sidebar" class="sticky top-0 h-screen shrink-0 border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-2 shadow-sm transition-all duration-300 ease-in-out w-64 flex flex-col">
    <div class="mb-6 border-b border-gray-200 dark:border-gray-800 pb-4">
        <div class="flex items-center justify-between rounded-md p-2">
            <div class="flex items-center gap-3">
                <div class="grid size-10 shrink-0 place-content-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 shadow-sm">
                    <svg width="20" height="20" viewBox="0 0 50 39" fill="none" xmlns="http://www.w3.org/2000/svg" class="fill-white">
                        <path d="M16.4992 2H37.5808L22.0816 24.9729H1L16.4992 2Z" />
                        <path d="M17.4224 27.102L11.4192 36H33.5008L49 13.0271H32.7024L23.2064 27.102H17.4224Z" />
                    </svg>
                </div>
                <div id="brand-text">
                    <span class="block text-sm font-semibold text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400">个人版</span>
                </div>
            </div>
        </div>
    </div>

    <nav class="flex-1 space-y-1">
        <?php
        $navItems = [
            ['icon' => 'dashboard', 'label' => '仪表板', 'href' => '/dashboard', 'section' => $section ?? ''],
            ['icon' => 'pages', 'label' => '我的页面', 'href' => '/pages', 'section' => $section ?? ''],
            ['icon' => 'link', 'label' => '链接管理', 'href' => '/dashboard', 'section' => 'links'],
            ['icon' => 'themes', 'label' => '主题', 'href' => '/themes', 'section' => $section ?? ''],
            ['icon' => 'chart', 'label' => '数据分析', 'href' => '/analytics', 'section' => $section ?? ''],
            ['icon' => 'enterprise', 'label' => '企业版', 'href' => '/enterprise', 'section' => $section ?? ''],
        ];
        $currentLabel = $currentSection ?? '仪表板';
        foreach ($navItems as $item):
            $isActive = ($item['label'] === $currentLabel);
        ?>
            <a href="<?php echo $item['href']; ?>"
               class="relative flex h-11 w-full items-center rounded-md transition-all duration-200 <?php echo $isActive ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 shadow-sm border-l-2 border-blue-500' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200'; ?>">
                <div class="grid h-full w-12 place-content-center">
                    <?php echo match($item['icon']) {
                        'dashboard' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>',
                        'pages' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                        'link' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>',
                        'themes' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>',
                        'chart' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                        'enterprise' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                        default => '',
                    }; ?>
                </div>
                <span class="text-sm font-medium"><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="border-t border-gray-200 dark:border-gray-800 pt-4 space-y-1">
        <div class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">账号</div>
        <a href="/admin"
           class="relative flex h-11 w-full items-center rounded-md transition-all duration-200 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200">
            <div class="grid h-full w-12 place-content-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-sm font-medium">管理后台</span>
        </a>
        <a href="#"
           class="relative flex h-11 w-full items-center rounded-md transition-all duration-200 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200">
            <div class="grid h-full w-12 place-content-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-sm font-medium">帮助</span>
        </a>
    </div>

    <button onclick="toggleSidebar()"
            class="mt-auto border-t border-gray-200 dark:border-gray-800 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors w-full text-left">
        <div class="flex items-center">
            <div class="grid size-10 place-content-center">
                <svg id="sidebar-toggle-icon" class="h-4 w-4 transition-transform duration-300 text-gray-500 dark:text-gray-400 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <span id="sidebar-toggle-text" class="text-sm font-medium text-gray-600 dark:text-gray-300">收起</span>
        </div>
    </button>
</aside>

<!-- Main Content -->
<main class="flex-1 p-6 overflow-auto">
<?php endif; ?>

<?php
// End of sidebar layout
if (!function_exists('sidebar_end')) {
    function sidebar_end() {
        echo '</main></div>';
        echo '<script src="' . url('assets/js/sidebar.js') . '"></script>';
        echo '</body></html>';
    }
}
?>
