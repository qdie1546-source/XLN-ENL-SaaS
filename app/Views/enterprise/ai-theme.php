<?php require BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="/enterprise" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">AI 创建主题</h1>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400">描述你想要的风格，AI 将自动生成主题 CSS。</p>

    <!-- Generation Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="/enterprise/ai-theme/generate" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">主题名称</label>
                <input type="text" name="name" required class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="例如：深色科技风">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">风格描述</label>
                <textarea name="description" required rows="3" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none" placeholder="例如：深色背景配蓝色渐变，卡片半透明毛玻璃效果，圆角按钮..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">主色调 (可选)</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="primary_color" value="#3b82f6" class="w-10 h-10 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer">
                    <span class="text-xs text-gray-500">选择主色调，帮助 AI 更好地匹配你的风格</span>
                </div>
            </div>

            <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-2.5 rounded-lg font-medium text-sm transition-all shadow-lg shadow-blue-500/25">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                生成主题
            </button>
        </form>
    </div>

    <!-- CSS 选择器参考 -->
    <details class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <summary class="px-6 py-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750 text-sm font-medium text-gray-700 dark:text-gray-300 select-none">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            CSS 选择器参考 — 主题可用的 HTML 结构
        </summary>
        <div class="px-6 pb-6 space-y-3 text-sm">
            <p class="text-gray-500 dark:text-gray-400">以下是 Link 页面中可用的 CSS 选择器，AI 生成主题 CSS 时请基于这些选择器编写样式。</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">body</code>
                    <p class="text-xs text-gray-500 mt-1">页面背景</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.container</code>
                    <p class="text-xs text-gray-500 mt-1">主容器（居中、最大宽度）</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.profile</code>
                    <p class="text-xs text-gray-500 mt-1">头像 + 名称 + 简介区域</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.avatar</code>
                    <p class="text-xs text-gray-500 mt-1">头像元素（默认 emoji 👤）</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.name</code>
                    <p class="text-xs text-gray-500 mt-1">用户名称标题 h1</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.bio</code>
                    <p class="text-xs text-gray-500 mt-1">个人简介段落</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.links</code>
                    <p class="text-xs text-gray-500 mt-1">所有链接的容器</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link</code>
                    <p class="text-xs text-gray-500 mt-1">普通链接卡片 a</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link-icon</code>
                    <p class="text-xs text-gray-500 mt-1">特殊链接内的图标 span</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link-phone</code>
                    <p class="text-xs text-gray-500 mt-1">电话链接</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link-qq</code>
                    <p class="text-xs text-gray-500 mt-1">QQ 链接</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link-wechat</code>
                    <p class="text-xs text-gray-500 mt-1">微信链接 div</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link-wechat-id</code>
                    <p class="text-xs text-gray-500 mt-1">微信号文本 span</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link-telegram</code>
                    <p class="text-xs text-gray-500 mt-1">Telegram 链接</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link-divider</code>
                    <p class="text-xs text-gray-500 mt-1">分割线 hr</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link-headline</code>
                    <p class="text-xs text-gray-500 mt-1">文字标题 h3</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link-text</code>
                    <p class="text-xs text-gray-500 mt-1">纯文本块 p</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.link-html</code>
                    <p class="text-xs text-gray-500 mt-1">自定义 HTML 块</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3">
                    <code class="text-xs text-blue-600 dark:text-blue-400 font-mono">.footer</code>
                    <p class="text-xs text-gray-500 mt-1">页脚区域</p>
                </div>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">提示：生成 AI 描述时可提及选择器和期望效果，如 "body 深色渐变背景，.link 卡片毛玻璃圆角，.avatar 白色边框圆形"</p>
        </div>
    </details>

    <!-- Generated Result -->
    <?php if (!empty($generatedCss ?? null)): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">生成结果</h2>

        <!-- Live Preview -->
        <style><?php echo $generatedCss; ?></style>
        <div class="mb-4 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="profile-section">
                <div class="avatar"></div>
                <h1 class="name">预览用户</h1>
                <p class="bio">这是一段示例简介，用于展示主题效果</p>
            </div>
            <div class="links">
                <a href="#" class="link-card">微信</a>
                <a href="#" class="link-card">微博</a>
                <a href="#" class="link-card">小红书</a>
                <a href="#" class="link-card">抖音</a>
            </div>
        </div>

        <!-- CSS Code -->
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">生成的 CSS</h3>
        <pre class="bg-gray-900 text-green-400 p-4 rounded-lg text-xs overflow-auto max-h-64"><code><?php echo htmlspecialchars($generatedCss); ?></code></pre>

        <!-- Save Action -->
        <form method="POST" action="/themes/upload" class="mt-4 flex items-center gap-3">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($generatedName ?? 'AI 主题'); ?>">
            <input type="hidden" name="description" value="<?php echo htmlspecialchars($generatedDesc ?? ''); ?>">
            <input type="hidden" name="css_content" value="<?php echo htmlspecialchars($generatedCss); ?>">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                保存主题
            </button>
            <span class="text-xs text-gray-500">保存后可直接应用</span>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
