<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>创建页面 - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans SC', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <a href="/dashboard" class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <span class="ml-3 text-xl font-bold text-slate-900"><?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></span>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-6 py-12">
        <div class="mb-8">
            <a href="/dashboard" class="text-slate-600 hover:text-slate-900 flex items-center mb-4 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                返回
            </a>
            <h1 class="text-3xl font-bold text-slate-900">创建新页面</h1>
        </div>

        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                <?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <form method="POST" action="/pages">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">页面地址</label>
                    <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
                        <span class="px-4 text-slate-500 bg-slate-50 border-r border-slate-300">/</span>
                        <input type="text" name="slug" required class="w-full px-4 py-3 focus:outline-none" placeholder="your-name">
                    </div>
                    <p class="text-xs text-slate-500 mt-2">这将是您的个人主页地址：linkhub.example.com/your-name</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">页面标题</label>
                    <input type="text" name="title" value="我的链接" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="显示在页面上的标题">
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-slate-700 mb-2">个人简介</label>
                    <textarea name="bio" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors resize-none" placeholder="简单介绍一下自己"></textarea>
                </div>

                <div class="flex gap-4">
                    <a href="/dashboard" class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 rounded-xl font-medium transition-colors">取消</a>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-medium shadow-sm transition-colors">创建页面</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
