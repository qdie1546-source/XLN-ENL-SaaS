<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册 - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans SC', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900"><?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></h1>
            <p class="text-slate-500 mt-2">创建新账号</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                    <?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">邮箱地址</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="请输入邮箱地址">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">手机号（可选）</label>
                    <input type="text" name="phone" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="请输入手机号">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">密码</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="至少8位字符">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">确认密码</label>
                    <input type="password" name="confirm_password" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="再次输入密码">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-medium shadow-sm transition-colors">
                    注册
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-slate-600">
                已有账号？
                <a href="/login" class="text-blue-600 hover:text-blue-700 font-medium">立即登录</a>
            </div>
        </div>

        <p class="text-center text-slate-400 text-sm mt-6">© 2026 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>. All rights reserved.</p>
    </div>
</body>
</html>
