<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>站点设置 - 管理后台</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="px-4 md:px-6 py-4">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <a href="/" class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <span class="ml-3 text-xl font-semibold tracking-tight text-gray-900"><?php echo htmlspecialchars(\ConfigHelper::siteName()); ?> 管理后台</span>
                </a>
                <div class="flex items-center gap-4">
                    <a href="/dashboard" class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors">返回用户后台</a>
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center text-blue-700 font-semibold text-sm"><?php echo mb_substr(htmlspecialchars($_SESSION['user']['name'] ?? 'A'), 0, 1); ?></div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 md:px-6 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <aside class="lg:col-span-3">
                <?php include __DIR__ . '/../partials/sidebar.php'; ?>
            </aside>

            <main class="lg:col-span-9 space-y-6">
                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
                <?php endif; ?>

                <h1 class="text-xl md:text-2xl font-semibold text-gray-900">站点设置</h1>

                <form action="/admin/config/update" method="POST" class="space-y-6">
                    <!-- General Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                常规设置
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    站点名称 <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="site_name" name="site_name"
                                       value="<?= htmlspecialchars($configs['site_name'] ?? 'LinkHub') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       placeholder="输入站点名称" required>
                                <p class="mt-1 text-sm text-gray-500">显示在页面标题和 Logo 旁的名称</p>
                            </div>
                            <div>
                                <label for="site_logo" class="block text-sm font-medium text-gray-700 mb-2">站点 Logo</label>
                                <input type="url" id="site_logo" name="site_logo"
                                       value="<?= htmlspecialchars($configs['site_logo'] ?? '') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       placeholder="https://example.com/logo.png">
                                <p class="mt-1 text-sm text-gray-500">输入 Logo 图片的 URL 地址（留空使用文字）</p>
                            </div>
                            <div>
                                <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">站点描述</label>
                                <input type="text" id="site_description" name="site_description"
                                       value="<?= htmlspecialchars($configs['site_description'] ?? '') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       placeholder="简短描述您的平台">
                                <p class="mt-1 text-sm text-gray-500">显示在首页的简短描述</p>
                            </div>
                        </div>
                    </div>

                    <!-- AI Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                AI 设置
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="ai_enabled" value="1"
                                       <?= ($configs['ai_enabled'] ?? true) ? 'checked' : '' ?>
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm font-medium text-gray-700">启用 AI 功能</span>
                            </label>
                            <p class="mt-1 ml-8 text-sm text-gray-500">关闭后用户将无法使用 AI 生成主题功能</p>

                            <div>
                                <label for="ai_api_key" class="block text-sm font-medium text-gray-700 mb-2">API 密钥</label>
                                <input type="password" id="ai_api_key" name="ai_api_key"
                                       value="<?= htmlspecialchars($configs['ai_api_key'] ?? '') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       placeholder="sk-...">
                                <p class="mt-1 text-sm text-gray-500">OpenAI 兼容 API 的密钥</p>
                            </div>
                            <div>
                                <label for="ai_api_endpoint" class="block text-sm font-medium text-gray-700 mb-2">API 端点</label>
                                <input type="url" id="ai_api_endpoint" name="ai_api_endpoint"
                                       value="<?= htmlspecialchars($configs['ai_api_endpoint'] ?? '') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       placeholder="https://api.openai.com/v1">
                                <p class="mt-1 text-sm text-gray-500">OpenAI 兼容 API 的地址</p>
                            </div>
                            <div>
                                <label for="ai_daily_limit" class="block text-sm font-medium text-gray-700 mb-2">每日调用上限</label>
                                <input type="number" id="ai_daily_limit" name="ai_daily_limit"
                                       value="<?= htmlspecialchars($configs['ai_daily_limit'] ?? '100') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       min="0">
                                <p class="mt-1 text-sm text-gray-500">每天最多允许的 AI 调用次数</p>
                            </div>
                            <div>
                                <label for="ai_model" class="block text-sm font-medium text-gray-700 mb-2">AI 模型</label>
                                <input type="text" id="ai_model" name="ai_model" list="ai-models"
                                       value="<?= htmlspecialchars($configs['ai_model'] ?? 'gpt-3.5-turbo') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       placeholder="输入模型名称，如 gpt-4、claude-3-opus、deepseek-chat">
                                <datalist id="ai-models">
                                    <option value="gpt-3.5-turbo">
                                    <option value="gpt-4">
                                    <option value="gpt-4-turbo">
                                    <option value="gpt-4o">
                                    <option value="claude-3-opus">
                                    <option value="claude-3-sonnet">
                                    <option value="deepseek-chat">
                                    <option value="deepseek-coder">
                                    <option value="gemini-2.0-flash">
                                    <option value="qwen-turbo">
                                    <option value="glm-4">
                                </datalist>
                                <p class="mt-1 text-sm text-gray-500">输入任意 OpenAI 兼容的模型名称，支持 NewAPI 等中转服务</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                支付设置
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Epay -->
                            <div class="pb-4 border-b border-gray-100">
                                <label class="flex items-center mb-4">
                                    <input type="checkbox" name="epay_enabled" value="1"
                                           <?= ($configs['epay_enabled'] ?? false) ? 'checked' : '' ?>
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">启用易支付</span>
                                </label>
                                <div class="space-y-3 ml-8">
                                    <div>
                                        <label for="epay_pid" class="block text-sm font-medium text-gray-700 mb-2">商户ID (PID)</label>
                                        <input type="text" id="epay_pid" name="epay_pid"
                                               value="<?= htmlspecialchars($configs['epay_pid'] ?? '') ?>"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                               placeholder="1001">
                                    </div>
                                    <div>
                                        <label for="epay_key" class="block text-sm font-medium text-gray-700 mb-2">商户密钥 (Key)</label>
                                        <input type="password" id="epay_key" name="epay_key"
                                               value="<?= htmlspecialchars($configs['epay_key'] ?? '') ?>"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                               placeholder="输入密钥">
                                    </div>
                                    <div>
                                        <label for="epay_api_url" class="block text-sm font-medium text-gray-700 mb-2">API 地址</label>
                                        <input type="url" id="epay_api_url" name="epay_api_url"
                                               value="<?= htmlspecialchars($configs['epay_api_url'] ?? '') ?>"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                               placeholder="https://pay.example.com/">
                                        <p class="mt-1 text-sm text-gray-500">易支付网关地址（以 / 结尾）</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="payment_alipay_enabled" value="1"
                                           <?= ($configs['payment_alipay_enabled'] ?? false) ? 'checked' : '' ?>
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">启用支付宝支付 (扫码)</span>
                                </label>
                            </div>
                            <div>
                                <label for="payment_alipay_qr" class="block text-sm font-medium text-gray-700 mb-2">支付宝收款二维码 URL</label>
                                <input type="url" id="payment_alipay_qr" name="payment_alipay_qr"
                                       value="<?= htmlspecialchars($configs['payment_alipay_qr'] ?? '') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       placeholder="https://example.com/alipay-qr.png">
                            </div>
                            <!-- Commission Rate -->
                            <div class="pt-4 pb-4 border-b border-gray-100">
                                <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">抽佣百分比 (%)</label>
                                <input type="number" id="commission_rate" name="commission_rate"
                                       value="<?= htmlspecialchars($configs['commission_rate'] ?? '20') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       min="0" max="100" step="1">
                                <p class="mt-1 text-sm text-gray-500">主题出售时平台收取的佣金比例，0 表示无佣金</p>
                            </div>
                            <div class="border-t border-gray-100 pt-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="payment_wechat_enabled" value="1"
                                           <?= ($configs['payment_wechat_enabled'] ?? false) ? 'checked' : '' ?>
                                           class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">启用微信支付 (扫码)</span>
                                </label>
                            </div>
                            <div>
                                <label for="payment_wechat_qr" class="block text-sm font-medium text-gray-700 mb-2">微信收款二维码 URL</label>
                                <input type="url" id="payment_wechat_qr" name="payment_wechat_qr"
                                       value="<?= htmlspecialchars($configs['payment_wechat_qr'] ?? '') ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       placeholder="https://example.com/wechat-qr.png">
                            </div>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                系统设置
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="maintenance_mode" value="1"
                                           <?= ($configs['maintenance_mode'] ?? false) ? 'checked' : '' ?>
                                           class="w-5 h-5 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">维护模式</span>
                                </label>
                                <p class="mt-1 ml-8 text-sm text-gray-500">开启后普通用户将无法访问网站</p>
                            </div>
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="allow_registration" value="1"
                                           <?= ($configs['allow_registration'] ?? true) ? 'checked' : '' ?>
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">允许新用户注册</span>
                                </label>
                                <p class="mt-1 ml-8 text-sm text-gray-500">关闭后新用户将无法注册账号</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg shadow-blue-500/25">
                            保存设置
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-6"><div class="text-center text-sm text-gray-500">&copy; 2026 <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?>. All rights reserved.</div></div>
    </footer>
</body>
</html>
