<?php
$title = $title ?? '我的钱包';
$section = $section ?? 'wallet';
require BASE_PATH . '/app/Views/layouts/header.php';
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">我的钱包</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">管理余额、充值、提现</p>
    </div>
</div>

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

<!-- Balance Card -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">可用余额</p>
        <p class="text-4xl font-bold text-gray-900 dark:text-gray-100">&yen;<?php echo number_format($balance, 2); ?></p>
    </div>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6 flex flex-col justify-center gap-4">
        <form method="POST" action="/wallet/deposit" class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">充值金额</label>
                <input type="number" name="amount" step="0.01" min="0.01" required
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="输入金额">
            </div>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm transition-colors">充值</button>
        </form>
        <form method="POST" action="/wallet/withdraw" class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">提现金额</label>
                <input type="number" name="amount" step="0.01" min="0.01" required
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="输入金额">
            </div>
            <button type="submit" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-800 text-white rounded-xl font-medium text-sm transition-colors">提现</button>
        </form>
    </div>
</div>

<!-- Transaction History -->
<div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">交易记录</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 text-left">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">类型</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">金额</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">描述</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">时间</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">暂无交易记录</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $t): ?>
                    <tr class="border-b border-gray-50 dark:border-gray-800/50 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-3">
                            <?php
                            $typeMap = [
                                'deposit' => ['充值', 'text-green-600 dark:text-green-400', 'bg-green-50 dark:bg-green-900/20'],
                                'withdraw' => ['提现', 'text-orange-600 dark:text-orange-400', 'bg-orange-50 dark:bg-orange-900/20'],
                                'purchase' => ['购买', 'text-blue-600 dark:text-blue-400', 'bg-blue-50 dark:bg-blue-900/20'],
                                'sale' => ['售出', 'text-purple-600 dark:text-purple-400', 'bg-purple-50 dark:bg-purple-900/20'],
                                'commission' => ['佣金', 'text-gray-600 dark:text-gray-400', 'bg-gray-50 dark:bg-gray-800'],
                            ];
                            $info = $typeMap[$t['type'] ?? ''] ?? [$t['type'] ?? '', 'text-gray-600', 'bg-gray-100'];
                            ?>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full <?php echo $info[1] . ' ' . $info[2]; ?>"><?php echo $info[0]; ?></span>
                        </td>
                        <td class="px-6 py-3 text-sm font-medium <?php echo floatval($t['amount'] ?? 0) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'; ?>">
                            <?php echo floatval($t['amount'] ?? 0) >= 0 ? '+' : ''; ?>&yen;<?php echo number_format(floatval($t['amount'] ?? 0), 2); ?>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($t['description'] ?? ''); ?></td>
                        <td class="px-6 py-3 text-sm text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($t['created_at'] ?? ''); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
