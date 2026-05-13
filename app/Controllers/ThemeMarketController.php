<?php

namespace App\Controllers;

use LinkHub\Models\UserTheme;
use LinkHub\Models\ThemePurchase;
use LinkHub\Models\Tag;
use App\Libraries\Session;

class ThemeMarketController extends Controller
{
    public function market()
    {
        // Redirect to unified theme page which already includes market themes
        $this->redirect('/themes');
    }

    public function upload()
    {
        $this->requireAuth();
        $this->view('themes/upload', [
            'section' => 'themes',
        ]);
    }

    public function doUpload()
    {
        $this->requireAuth();
        $user = $this->auth();

        $name = $this->getPost('name', '');
        $description = $this->getPost('description', '');
        $cssContent = $this->getPost('css_content', '');

        if (empty($name) || empty($cssContent)) {
            $_SESSION['flash']['error'] = '请填写主题名称和 CSS 内容';
            $this->redirect('/themes/upload');
        }

        $slug = $this->slugify($name) . '-' . substr(uniqid(), -6);

        $userThemeModel = new UserTheme();
        $price = max(0, floatval($this->getPost('price', 0)));

        $themeId = $userThemeModel->create([
            'user_id' => $user['id'],
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'css_content' => $cssContent,
            'price' => $price,
            'status' => 'pending',
            'is_active' => 1,
        ]);

        // Save tags
        $tagIds = $_POST['tag_ids'] ?? [];
        if (!empty($tagIds)) {
            $tagModel = new Tag();
            $tagModel->setThemeTags($themeId, $tagIds, 'user');
        }

        $_SESSION['flash']['success'] = '主题已提交，等待管理员审核';
        $this->redirect('/themes/my');
    }

    public function myThemes()
    {
        $this->requireAuth();
        $user = $this->auth();

        $userThemeModel = new UserTheme();
        $myThemes = $userThemeModel->findByUser($user['id']);
        $purchasedThemes = $userThemeModel->purchasedByUser($user['id']);

        $this->view('themes/my-themes', [
            'section' => 'themes',
            'myThemes' => $myThemes,
            'purchasedThemes' => $purchasedThemes,
        ]);
    }

    public function purchase($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        $userThemeModel = new UserTheme();
        $theme = $userThemeModel->find($id);

        if (!$theme || $theme['status'] !== 'approved') {
            $_SESSION['flash']['error'] = '主题不可用';
            $this->redirect('/themes/market');
        }

        if ($theme['user_id'] == $user['id']) {
            $_SESSION['flash']['error'] = '您不能购买自己上传的主题';
            $this->redirect('/themes/market');
        }

        $purchaseModel = new ThemePurchase();
        if ($purchaseModel->hasPurchased($user['id'], $id)) {
            $_SESSION['flash']['success'] = '您已购买过该主题，可以直接使用';
            $this->redirect('/themes/my');
        }

        $price = floatval($theme['price'] ?? 0);

        if ($price > 0) {
            $walletModel = new \LinkHub\Models\Wallet();
            $txnModel = new \LinkHub\Models\Transaction();

            if (!$walletModel->deductBalance($user['id'], $price)) {
                $_SESSION['flash']['error'] = '余额不足，请先充值';
                $this->redirect('/themes/market');
            }
            $txnModel->recordTransaction($user['id'], 'purchase', -$price, $id, "购买主题：{$theme['name']}");

            // Credit seller minus commission
            $db = \App\Libraries\Database::getInstance();
            $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
            $row = $db->fetch("SELECT `value` FROM `{$prefix}config` WHERE `key` = 'commission_rate'");
            $commissionRate = floatval($row['value'] ?? '20') / 100;
            $commission = $price * $commissionRate;
            $sellerAmount = $price - $commission;

            $walletModel->addBalance($theme['user_id'], $sellerAmount);
            $txnModel->recordTransaction($theme['user_id'], 'sale', $sellerAmount, $id, "主题售出：{$theme['name']}");
            $txnModel->recordTransaction(0, 'commission', $commission, $id, "平台佣金：{$theme['name']}");
        }

        // Create purchase record
        $purchaseModel->create([
            'buyer_id' => $user['id'],
            'theme_id' => $id,
            'price_paid' => $price,
        ]);

        $_SESSION['flash']['success'] = '成功获取主题：「' . htmlspecialchars($theme['name']) . '」';
        $this->redirect('/themes/my');
    }

    public function apply($id)
    {
        $this->requireAuth();
        $user = $this->auth();

        $userThemeModel = new UserTheme();
        $theme = $userThemeModel->find($id);

        if (!$theme) {
            $_SESSION['flash']['error'] = '主题不存在';
            $this->redirect('/themes/my');
        }

        // Can apply if: author, or has purchased, or is free (price = 0 and approved)
        $purchaseModel = new ThemePurchase();
        $canApply = $theme['user_id'] == $user['id']
            || $purchaseModel->hasPurchased($user['id'], $id)
            || ($theme['price'] == 0 && $theme['status'] === 'approved');

        if (!$canApply) {
            $_SESSION['flash']['error'] = '请先获取该主题';
            $this->redirect('/themes/market');
        }

        // Apply to user's default page
        $pageModel = new \LinkHub\Models\Page();
        $pages = $pageModel->findByUser($user['id']);

        if (empty($pages)) {
            $_SESSION['flash']['error'] = '请先创建页面';
            $this->redirect('/pages/create');
        }

        // Store theme CSS in page's custom_css
        $pageModel->update($pages[0]['id'], [
            'custom_css' => $theme['css_content'],
        ]);

        $_SESSION['flash']['success'] = '主题「' . htmlspecialchars($theme['name']) . '」已应用';
        $this->redirect('/pages');
    }

    private function slugify($text)
    {
        $text = preg_replace('/[^\x{4e00}-\x{9fff}a-zA-Z0-9]+/u', '-', $text);
        $text = trim($text, '-');
        return strtolower($text) ?: 'theme';
    }
}
