<?php
$title = '主题市场';
$section = 'themes';
require BASE_PATH . '/app/Views/layouts/header.php';
?>

<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">主题市场</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">选择一款精美主题，打造独特的个人主页</p>
    </div>
    <div class="flex items-center gap-4">
        <button onclick="toggleDark()"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            <svg id="dark-icon-moon" class="h-4 w-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <svg id="dark-icon-sun" class="h-4 w-4 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>
    </div>
</div>

<!-- Flash Messages -->
<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm flex items-center gap-2">
        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm flex items-center gap-2">
        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?>
    </div>
<?php endif; ?>

<!-- Filter Buttons -->
<div class="flex gap-2 mb-8" id="theme-filters">
    <button onclick="filterThemes('all')" data-filter="all"
            class="px-4 py-2 rounded-xl text-sm font-medium border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors filter-btn active bg-blue-600 text-white border-blue-600 dark:bg-blue-600 dark:text-white">
        全部主题
    </button>
    <button onclick="filterThemes('free')" data-filter="free"
            class="px-4 py-2 rounded-xl text-sm font-medium border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors filter-btn">
        免费
    </button>
    <button onclick="filterThemes('premium')" data-filter="premium"
            class="px-4 py-2 rounded-xl text-sm font-medium border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors filter-btn">
        付费
    </button>
</div>

<!-- Theme Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($themes as $theme):
            $slug = $theme["slug"] ?? "";
            $source = $theme['source'] ?? 'system';
            $price = floatval($theme['price'] ?? 0);
            $isp = $theme['is_free'] ? 'true' : 'false';
    ?>
        <div class="theme-card rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm hover:shadow-lg transition-all overflow-hidden"
             data-free="<?php echo $isp; ?>">
            <!-- Theme Preview -->
            <div class="h-44 relative overflow-hidden theme-preview-<?php echo $slug; ?>">
                <?php
                $gradients = [
                    'minimal' => 'from-slate-50 to-slate-200',
                    'gradient' => 'from-purple-500 via-pink-500 to-orange-400',
                    'card' => 'from-blue-100 to-indigo-200',
                    'dark' => 'from-gray-800 to-gray-950',
                    'vintage' => 'from-amber-100 to-orange-200',
                ];
                $bg = $gradients[$slug] ?? 'from-slate-100 to-slate-200';
                ?>
                <div class="absolute inset-0 bg-gradient-to-br <?php echo $bg; ?> flex items-center justify-center">
                    <div class="w-3/4 bg-white/90 dark:bg-gray-800/90 backdrop-blur rounded-xl shadow-lg p-4 space-y-2">
                        <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-1/2 mx-auto"></div>
                        <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mx-auto"></div>
                        <div class="h-7 bg-gray-300 dark:bg-gray-600 rounded mt-2"></div>
                        <div class="h-7 bg-gray-300 dark:bg-gray-600 rounded"></div>
                        <div class="h-7 bg-gray-300 dark:bg-gray-600 rounded"></div>
                    </div>
                </div>
                <?php if ($price > 0): ?>
                    <span class="absolute top-3 right-3 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-500 text-white">
                        &yen;<?php echo number_format($price, 2); ?>
                    </span>
                <?php else: ?>
                    <span class="absolute top-3 right-3 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-500 text-white">免费</span>
                <?php endif; ?>
                <?php if ($source === 'user'): ?>
                    <span class="absolute top-3 left-3 text-xs px-2 py-0.5 rounded-full bg-blue-500/80 text-white">用户</span>
                <?php endif; ?>
            </div>

            <div class="p-5">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1"><?php echo htmlspecialchars($theme['name']); ?></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1"><?php echo htmlspecialchars($theme['description'] ?? ''); ?></p>
                <?php if (!empty($theme['tags'])): ?>
                <div class="flex flex-wrap gap-1 mb-2">
                    <?php foreach ($theme['tags'] as $t): ?>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($t['name']); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">作者: <?php echo htmlspecialchars($theme['author_name'] ?? \ConfigHelper::siteName()); ?></p>
                <div class="flex gap-3">
                    <a href="/themes/preview/<?php echo $slug; ?>"
                       class="flex-1 text-center py-2.5 rounded-xl text-sm font-medium border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        预览
                    </a>
                    <?php if ($source === 'user' && $price > 0): ?>
                        <a href="/checkout?type=theme&id=<?php echo $theme['id']; ?>" class="flex-1 block text-center py-2.5 rounded-xl text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-colors">购买</a>
                    <?php else: ?>
                        <button onclick='openApplyModal("<?php echo $slug; ?>", "<?php echo htmlspecialchars($theme['name'], ENT_QUOTES); ?>", "<?php echo $source; ?>")'
                                class="flex-1 py-2.5 rounded-xl text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-colors">
                            <?php echo $source === 'user' ? '获取' : '应用'; ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (empty($themes)): ?>
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
        </div>
        <p class="text-gray-500 dark:text-gray-400">暂无可用主题</p>
    </div>
<?php endif; ?>

<!-- Apply Modal -->
<div id="apply-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">应用主题</h3>
            <button onclick="closeApplyModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            将 <strong id="modal-theme-name" class="text-gray-700 dark:text-gray-300"></strong> 应用到：
        </p>
        <form id="apply-form" method="POST" action="">
            <div class="space-y-2 max-h-64 overflow-y-auto">
                <?php foreach ($pages as $page): ?>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition-colors">
                        <input type="radio" name="page_id" value="<?php echo $page['id']; ?>" class="text-blue-600 focus:ring-blue-500" required>
                        <div>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars($page['title']); ?></span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">/<?php echo htmlspecialchars($page['slug']); ?></span>
                        </div>
                    </label>
                <?php endforeach; ?>
                <?php if (empty($pages)): ?>
                    <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">
                        还没有页面，请先<a href="/pages/create" class="text-blue-600 hover:underline">创建页面</a>
                    </p>
                <?php endif; ?>
            </div>
            <input type="hidden" name="theme_slug" id="modal-theme-slug" value="">
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeApplyModal()"
                        class="flex-1 py-2.5 rounded-xl text-sm font-medium border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    取消
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 rounded-xl text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-colors disabled:opacity-50"
                        <?php echo empty($pages) ? 'disabled' : ''; ?>>
                    确认应用
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterThemes(type) {
    document.querySelectorAll('.filter-btn').forEach(function(btn) {
        btn.classList.remove('active', 'bg-blue-600', 'text-white', 'border-blue-600', 'dark:bg-blue-600', 'dark:text-white');
        btn.classList.add('bg-white', 'dark:bg-gray-900', 'text-gray-700', 'dark:text-gray-300', 'border-gray-200', 'dark:border-gray-700');
    });
    var active = document.querySelector('[data-filter="' + type + '"]');
    if (active) {
        active.classList.remove('bg-white', 'dark:bg-gray-900', 'text-gray-700', 'dark:text-gray-300', 'border-gray-200', 'dark:border-gray-700');
        active.classList.add('active', 'bg-blue-600', 'text-white', 'border-blue-600', 'dark:bg-blue-600', 'dark:text-white');
    }

    document.querySelectorAll('.theme-card').forEach(function(card) {
        var isFree = card.getAttribute('data-free') === 'true';
        if (type === 'all') { card.style.display = ''; }
        else if (type === 'free') { card.style.display = isFree ? '' : 'none'; }
        else { card.style.display = isFree ? 'none' : ''; }
    });
}

function openApplyModal(slug, name, source) {
    document.getElementById('modal-theme-slug').value = slug;
    document.getElementById('modal-theme-name').textContent = name;
    document.getElementById('apply-form').action = '/themes/' + slug + '/apply-to';
    document.getElementById('apply-modal').classList.remove('hidden');
    document.getElementById('apply-modal').classList.add('flex');
}

function closeApplyModal() {
    document.getElementById('apply-modal').classList.add('hidden');
    document.getElementById('apply-modal').classList.remove('flex');
}

document.getElementById('apply-modal').addEventListener('click', function(e) {
    if (e.target === this) { closeApplyModal(); }
});
</script>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
