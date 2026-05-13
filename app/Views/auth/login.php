<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户登录 - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
    <script>
        const SITE_CONFIG = {
            site_name: '<?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>',
            site_logo: '<?php echo htmlspecialchars(\ConfigHelper::get('site_logo', '')); ?>',
            site_description: '<?php echo htmlspecialchars(\ConfigHelper::get('site_description', '社交链接聚合平台')); ?>'
        };
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">欢迎回来</h1>
                <p class="text-gray-500">登录到你的 <span data-site-name><?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></span> 账号</p>
            </div>

            <?php if (isset($error)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form action="/login" method="POST" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        邮箱地址
                    </label>
                    <input type="email" id="email" name="email" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                           placeholder="your@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        密码
                    </label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">记住我</span>
                    </label>
                    <a href="/forgot-password" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        忘记密码？
                    </a>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white py-3 rounded-xl font-semibold transition-all shadow-lg shadow-blue-500/25">
                    登录
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    还没有账号？
                    <a href="/register" class="text-blue-600 hover:text-blue-700 font-medium">立即注册</a>
                </p>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <a href="/admin/login" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-medium text-center transition-colors text-sm">
                    管理员登录
                </a>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="/" class="text-gray-500 hover:text-gray-700 text-sm">
                ← 返回首页
            </a>
        </div>
    </div>
</body>
</html>
