<?php
$title = '后台设置';
$section = 'enterprise-settings';
require BASE_PATH . '/app/Views/layouts/header.php';
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">后台设置</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">管理企业信息和品牌设置</p>
    </div>
    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-full">企业版</span>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Company Info -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">企业信息</h3>
        <form method="POST" action="/enterprise">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">公司名称</label>
                    <input type="text" name="company_name" value="<?php echo htmlspecialchars($profile['company_name'] ?? ''); ?>"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">行业</label>
                    <input type="text" name="industry" value="<?php echo htmlspecialchars($profile['industry'] ?? ''); ?>"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">公司规模</label>
                    <select name="company_size" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">请选择</option>
                        <option value="1-10" <?php echo ($profile['company_size'] ?? '') === '1-10' ? 'selected' : ''; ?>>1-10 人</option>
                        <option value="11-50" <?php echo ($profile['company_size'] ?? '') === '11-50' ? 'selected' : ''; ?>>11-50 人</option>
                        <option value="51-200" <?php echo ($profile['company_size'] ?? '') === '51-200' ? 'selected' : ''; ?>>51-200 人</option>
                        <option value="201-500" <?php echo ($profile['company_size'] ?? '') === '201-500' ? 'selected' : ''; ?>>201-500 人</option>
                        <option value="500+" <?php echo ($profile['company_size'] ?? '') === '500+' ? 'selected' : ''; ?>>500+ 人</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm shadow-sm transition-colors">
                    保存企业信息
                </button>
            </div>
        </form>
    </div>

    <!-- Custom Domain -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">自定义域名</h3>
        <form method="POST" action="/enterprise/domain">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">自定义域名</label>
                    <input type="text" name="custom_domain" value="<?php echo htmlspecialchars($profile['custom_domain'] ?? ''); ?>" placeholder="mycompany.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">配置后将您的域名 CNAME 到我们的服务器</p>
                </div>
                <?php if (!empty($profile['custom_domain'])): ?>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-sm text-blue-700 dark:text-blue-300">
                        当前域名：<strong><?php echo htmlspecialchars($profile['custom_domain']); ?></strong>
                    </div>
                <?php endif; ?>
                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm shadow-sm transition-colors">
                    保存域名设置
                </button>
            </div>
        </form>
    </div>

    <!-- Branding -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">品牌设置</h3>
        <form method="POST" action="/enterprise">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">品牌主色</label>
                    <div class="flex gap-3">
                        <input type="color" name="brand_color" value="<?php echo htmlspecialchars($profile['brand_color'] ?? '#3B82F6'); ?>"
                               class="h-10 w-16 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer">
                        <input type="text" name="brand_color_text" value="<?php echo htmlspecialchars($profile['brand_color'] ?? '#3B82F6'); ?>"
                               class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-mono">
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm shadow-sm transition-colors">
                    保存品牌设置
                </button>
            </div>
        </form>
    </div>
</div>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
