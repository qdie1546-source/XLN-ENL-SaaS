<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(\ConfigHelper::siteName()); ?> - 社交链接聚合平台</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans SC', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <span class="ml-3 text-xl font-bold text-slate-900"><?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/login" class="text-slate-600 hover:text-slate-900 font-medium transition-colors">登录</a>
                    <a href="/register" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm transition-colors">注册</a>
                </div>
            </div>
        </div>
    </header>

    <section class="py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6">
                    一个链接，汇聚所有
                </h1>
                <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                    轻松聚合您的所有社交链接，创建一个专业的个人主页
                </p>
                <div class="mt-10 flex justify-center gap-4">
                    <a href="/register" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-medium shadow-sm transition-colors text-lg">
                        免费开始
                    </a>
                </div>
            </div>

            <div class="bg-slate-100 rounded-2xl p-8 border border-slate-200">
                <div class="bg-white rounded-xl p-8 shadow-sm max-w-md mx-auto">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-slate-200 rounded-full mx-auto mb-4"></div>
                        <h3 class="text-xl font-semibold text-slate-900">您的名字</h3>
                        <p class="text-slate-500">简介文字</p>
                    </div>
                    <div class="space-y-3">
                        <div class="bg-slate-100 rounded-xl py-3 text-center text-slate-600">链接 1</div>
                        <div class="bg-slate-100 rounded-xl py-3 text-center text-slate-600">链接 2</div>
                        <div class="bg-slate-100 rounded-xl py-3 text-center text-slate-600">链接 3</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-slate-900 text-center mb-12">主要功能</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-6 rounded-2xl border border-slate-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">链接管理</h3>
                    <p class="text-slate-600">轻松添加、编辑和排序所有社交媒体链接</p>
                </div>
                <div class="p-6 rounded-2xl border border-slate-200">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">精美主题</h3>
                    <p class="text-slate-600">多种精美主题可选，打造独特个人风格</p>
                </div>
                <div class="p-6 rounded-2xl border border-slate-200">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">数据统计</h3>
                    <p class="text-slate-600">详细的点击统计，了解访问者动态</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-12 border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-6 text-center text-slate-500">
            <p>© 2026 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
