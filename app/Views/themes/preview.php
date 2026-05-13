<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($theme['name']); ?> 主题预览 - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
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
                <a href="/themes" class="text-slate-600 hover:text-slate-900 transition-colors">返回主题市场</a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-900 mb-2"><?php echo htmlspecialchars($theme['name']); ?> 主题预览</h1>
            <p class="text-slate-600"><?php echo htmlspecialchars($theme['description'] ?? ''); ?></p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-8 overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200">
                <h3 class="font-medium text-slate-700">预览效果</h3>
            </div>
            <div class="bg-white p-8">
                <iframe src="/preview/<?php echo $slug; ?>" class="w-full h-96 border-0" title="Theme Preview"></iframe>
            </div>
        </div>

        <div class="text-center">
            <a href="/dashboard" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium shadow-sm transition-colors">
                返回后台
            </a>
        </div>
    </main>
</body>
</html>
