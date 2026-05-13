<?php
$title = $title ?? '收银台';
$section = $section ?? 'checkout';
require BASE_PATH . '/app/Views/layouts/header.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">收银台</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">确认订单并选择支付方式</p>
    </div>

    <?php if (isset($_SESSION['flash']['error'])): ?>
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
    <?php endif; ?>

    <!-- Order Summary -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">订单摘要</h3>
        <div class="space-y-3">
            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                <span class="text-gray-500 dark:text-gray-400">商品名称</span>
                <span class="text-gray-900 dark:text-gray-100 font-medium"><?php echo htmlspecialchars($itemName); ?></span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                <span class="text-gray-500 dark:text-gray-400">商品类型</span>
                <span class="text-gray-900 dark:text-gray-100"><?php echo $orderType === 'plan' ? '套餐' : ($orderType === 'theme' ? '主题' : '钱包充值'); ?></span>
            </div>
            <div class="flex justify-between py-3">
                <span class="text-gray-700 dark:text-gray-300 font-semibold">应付金额</span>
                <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">&yen;<?php echo number_format($price, 2); ?></span>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">选择支付方式</h3>

        <?php
            $defaultPaymentMethod = 'wallet';
            if (!empty($config['epay_enabled']) && !empty($config['epay_pid']) && !empty($config['epay_key']) && !empty($config['epay_api_url'])) {
                $defaultPaymentMethod = 'epay';
            }
        ?>

        <form method="POST" action="/checkout/process">
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($orderType); ?>">
            <input type="hidden" name="id" value="<?php echo $orderId; ?>">
            <?php if ($orderType === 'wallet'): ?>
                <input type="hidden" name="amount" value="<?php echo htmlspecialchars($price); ?>">
            <?php endif; ?>

            <div class="space-y-3 mb-6">
                <!-- Wallet Balance Payment (always available) -->
                <label class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-blue-500 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/10">
                    <input type="radio" name="payment_method" value="wallet" class="w-4 h-4 text-blue-600" checked>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <span class="font-medium text-gray-900 dark:text-gray-100">钱包余额支付</span>
                    </div>
                </label>

                <?php if (!empty($config['payment_alipay_enabled']) && !empty($config['payment_alipay_qr'])): ?>
                    <label class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-blue-500 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/10">
                        <input type="radio" name="payment_method" value="alipay" class="w-4 h-4 text-blue-600" <?php echo $defaultPaymentMethod === 'alipay' ? 'checked' : ''; ?>>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xs">支</span>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-gray-100">支付宝</span>
                        </div>
                    </label>
                <?php endif; ?>

                <?php if (!empty($config['payment_wechat_enabled']) && !empty($config['payment_wechat_qr'])): ?>
                    <label class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-green-500 transition-colors has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/10">
                        <input type="radio" name="payment_method" value="wechat" class="w-4 h-4 text-green-600" <?php echo $defaultPaymentMethod === 'wechat' ? 'checked' : ''; ?>>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xs">微</span>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-gray-100">微信支付</span>
                        </div>
                    </label>
                <?php endif; ?>

                <?php if (!empty($config['epay_enabled']) && !empty($config['epay_pid']) && !empty($config['epay_key']) && !empty($config['epay_api_url'])): ?>
                    <label class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-indigo-500 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/10">
                        <input type="radio" name="payment_method" value="epay" class="w-4 h-4 text-indigo-600" <?php echo $defaultPaymentMethod === 'epay' ? 'checked' : ''; ?>>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xs">易</span>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-gray-100">易支付</span>
                        </div>
                    </label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 ml-11">易支付会异步回调订单状态，支付完成后系统会自动确认。</p>
                <?php endif; ?>

                <?php if ((empty($config['payment_alipay_enabled']) || empty($config['payment_alipay_qr'])) && (empty($config['payment_wechat_enabled']) || empty($config['payment_wechat_qr'])) && empty($config['epay_enabled'])): ?>
                    <p class="text-sm text-gray-500 dark:text-gray-400 ml-11">暂未配置第三方支付，将使用钱包余额支付</p>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm shadow-sm transition-colors">
                确认支付 &yen;<?php echo number_format($price, 2); ?>
            </button>

            <p class="text-xs text-gray-400 dark:text-gray-500 text-center mt-3">
                支付成功后会自动跳转
            </p>
        </form>
    </div>

    <!-- QR Code Display -->
    <?php if (!empty($config['payment_alipay_qr'])): ?>
        <div id="qrcode-alipay" class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6 mb-6 text-center hidden">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">支付宝扫码支付</h3>
            <img src="<?php echo htmlspecialchars($config['payment_alipay_qr']); ?>" alt="支付宝收款码" class="max-w-xs mx-auto rounded-lg">
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">请使用支付宝扫描二维码完成支付</p>
        </div>
    <?php endif; ?>
    <?php if (!empty($config['payment_wechat_qr'])): ?>
        <div id="qrcode-wechat" class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6 mb-6 text-center hidden">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">微信扫码支付</h3>
            <img src="<?php echo htmlspecialchars($config['payment_wechat_qr']); ?>" alt="微信收款码" class="max-w-xs mx-auto rounded-lg">
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">请使用微信扫描二维码完成支付</p>
        </div>
    <?php endif; ?>
</div>

<script>
(function() {
    var radios = document.querySelectorAll('input[name="payment_method"]');
    function updateQR() {
        document.getElementById('qrcode-alipay')?.classList.add('hidden');
        document.getElementById('qrcode-wechat')?.classList.add('hidden');
        var selected = document.querySelector('input[name="payment_method"]:checked');
        if (selected && selected.value === 'alipay') {
            document.getElementById('qrcode-alipay')?.classList.remove('hidden');
        } else if (selected && selected.value === 'wechat') {
            document.getElementById('qrcode-wechat')?.classList.remove('hidden');
        }
    }
    radios.forEach(function(r) { r.addEventListener('change', updateQR); });
    updateQR();
})();
</script>

<?php require BASE_PATH . '/app/Views/layouts/footer.php'; ?>
