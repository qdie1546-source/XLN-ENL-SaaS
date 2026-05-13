<?php

namespace App\Controllers;

use LinkHub\Models\User;

class EnterpriseController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $user = $this->auth();

        $userModel = new User();
        $profile = $userModel->enterpriseProfile($user['id']);

        if ($profile) {
            if (isset($profile['is_active']) && !$profile['is_active']) {
                $_SESSION['flash']['error'] = '您的企业账号已被停用，请联系管理员';
                $this->redirect('/dashboard');
            }
            $this->view('enterprise/dashboard', [
                'section' => 'enterprise',
                'profile' => $profile,
                'authUser' => $user,
            ]);
        } else {
            $this->view('enterprise/upgrade');
        }
    }

    public function update()
    {
        $this->requireAuth();
        $user = $this->auth();

        $userModel = new User();
        $profile = $userModel->enterpriseProfile($user['id']);

        if (!$profile) {
            $_SESSION['flash']['error'] = '请先开通企业版';
            $this->redirect('/enterprise');
        }

        if (isset($profile['is_active']) && !$profile['is_active']) {
            $_SESSION['flash']['error'] = '您的企业账号已被停用';
            $this->redirect('/dashboard');
        }

        $data = [
            'company_name' => $_POST['company_name'] ?? $profile['company_name'] ?? '',
            'industry' => $_POST['industry'] ?? $profile['industry'] ?? '',
            'company_size' => $_POST['company_size'] ?? $profile['company_size'] ?? '',
        ];

        if (!empty($_POST['brand_color'])) {
            $data['brand_color'] = $_POST['brand_color'];
        }

        $userModel->update($user['id'], ['user_type' => 'enterprise']);
        $this->updateProfile($user['id'], array_merge($profile, $data));

        $_SESSION['flash']['success'] = '企业信息已更新';
        $this->redirect('/enterprise');
    }

    public function domain()
    {
        $this->requireAuth();
        $user = $this->auth();

        $userModel = new User();
        $profile = $userModel->enterpriseProfile($user['id']);

        if (!$profile) {
            $_SESSION['flash']['error'] = '请先开通企业版';
            $this->redirect('/enterprise');
        }

        if (isset($profile['is_active']) && !$profile['is_active']) {
            $_SESSION['flash']['error'] = '您的企业账号已被停用';
            $this->redirect('/dashboard');
        }

        $domain = $_POST['custom_domain'] ?? '';
        $domain = trim($domain);

        if (!empty($domain) && !preg_match('/^[a-zA-Z0-9][a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $domain)) {
            $_SESSION['flash']['error'] = '域名格式不正确';
            $this->redirect('/enterprise');
        }

        $this->updateProfile($user['id'], array_merge($profile, [
            'custom_domain' => $domain,
        ]));

        $_SESSION['flash']['success'] = '域名设置已更新';
        $this->redirect('/enterprise');
    }

    public function activate()
    {
        $this->requireAuth();
        $user = $this->auth();

        $userModel = new User();
        $existing = $userModel->enterpriseProfile($user['id']);

        if ($existing) {
            $_SESSION['flash']['success'] = '您已开通企业版';
            $this->redirect('/enterprise');
        }

        // Create enterprise profile
        $db = \App\Libraries\Database::getInstance();
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');
        $db->query(
            "INSERT INTO `{$prefix}enterprise_profiles` (`user_id`, `company_name`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?)",
            [$user['id'], '', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]
        );

        $userModel->update($user['id'], ['user_type' => 'enterprise']);

        $_SESSION['flash']['success'] = '恭喜！您已成功开通企业版';
        $this->redirect('/enterprise');
    }

    public function settings()
    {
        $this->requireAuth();
        $user = $this->auth();

        $userModel = new User();
        $profile = $userModel->enterpriseProfile($user['id']);

        if (!$profile) {
            $_SESSION['flash']['error'] = '请先开通企业版';
            $this->redirect('/enterprise');
        }

        if (isset($profile['is_active']) && !$profile['is_active']) {
            $_SESSION['flash']['error'] = '您的企业账号已被停用';
            $this->redirect('/dashboard');
        }

        $this->view('enterprise/settings', [
            'section' => 'enterprise-settings',
            'profile' => $profile,
        ]);
    }

    public function aiTheme()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (($user['user_type'] ?? '') !== 'enterprise') {
            $_SESSION['flash']['error'] = '此功能仅限企业版用户使用';
            $this->redirect('/enterprise');
        }

        $userModel = new User();
        $profile = $userModel->enterpriseProfile($user['id']);
        if ($profile && isset($profile['is_active']) && !$profile['is_active']) {
            $_SESSION['flash']['error'] = '您的企业账号已被停用';
            $this->redirect('/dashboard');
        }

        $this->view('enterprise/ai-theme', [
            'section' => 'ai-theme',
        ]);
    }

    public function generateTheme()
    {
        $this->requireAuth();
        $user = $this->auth();

        if (($user['user_type'] ?? '') !== 'enterprise') {
            $_SESSION['flash']['error'] = '此功能仅限企业版用户使用';
            $this->redirect('/enterprise');
        }

        $userModel = new User();
        $profile = $userModel->enterpriseProfile($user['id']);
        if ($profile && isset($profile['is_active']) && !$profile['is_active']) {
            $_SESSION['flash']['error'] = '您的企业账号已被停用';
            $this->redirect('/dashboard');
        }

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $primaryColor = $_POST['primary_color'] ?? '#3b82f6';

        if (empty($name) || empty($description)) {
            $_SESSION['flash']['error'] = '请填写主题名称和风格描述';
            $this->redirect('/enterprise/ai-theme');
        }

        $css = $this->generateCSS($name, $description, $primaryColor);

        $this->view('enterprise/ai-theme', [
            'section' => 'ai-theme',
            'generatedCss' => $css,
            'generatedName' => $name,
            'generatedDesc' => $description,
        ]);
    }

    private function callAIForCSS($name, $description, $primaryColor)
    {
        $db = \App\Libraries\Database::getInstance();
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');

        $apiKey = $db->fetch("SELECT `value` FROM `{$prefix}config` WHERE `key` = 'ai_api_key'")['value'] ?? '';
        $endpoint = $db->fetch("SELECT `value` FROM `{$prefix}config` WHERE `key` = 'ai_api_endpoint'")['value'] ?? '';
        $model = $db->fetch("SELECT `value` FROM `{$prefix}config` WHERE `key` = 'ai_model'")['value'] ?? 'gpt-3.5-turbo';
        $dailyLimit = intval($db->fetch("SELECT `value` FROM `{$prefix}config` WHERE `key` = 'ai_daily_limit'")['value'] ?? '100');

        if (empty($apiKey) || empty($endpoint)) {
            return null;
        }

        // Check daily limit
        $today = date('Y-m-d');
        $count = $db->fetch(
            "SELECT COUNT(*) as cnt FROM `{$prefix}config` WHERE `key` = ? AND `updated_at` LIKE ?",
            ['ai_daily_count_' . $today, $today . '%']
        )['cnt'] ?? 0;
        // Actually check from the value
        $todayCount = intval($db->fetch("SELECT `value` FROM `{$prefix}config` WHERE `key` = ?", ['ai_daily_count_' . $today])['value'] ?? '0');
        if ($todayCount >= $dailyLimit) {
            return null;
        }

        $prompt = "你是一个CSS设计专家。根据以下描述生成一个社交链接聚合页面的CSS样式代码。只输出纯CSS代码，不要输出任何解释、markdown标记或代码块标记。

主题名称：{$name}
风格描述：{$description}
主色调：{$primaryColor}

CSS必须基于以下HTML结构编写（使用这些选择器）：

页面结构：
- body: 页面背景、全局字体
- .container: 主容器，max-width居中，padding
- .profile: 头像+名称+简介区域，居中排列
- .avatar: 头像元素（可用::before添加内容，或设置宽高边框阴影）
- .name: 用户名称 h1
- .bio: 个人简介 p
- .links: 链接列表容器（flex/grid布局）
- .link: 链接卡片 a（display:flex, align-items:center, gap, padding, border-radius, background, transition）
- a:hover, .link:hover: 悬停效果（transform, box-shadow等）
- .link-icon: 特殊链接内的图标 span
- .link-phone: 电话类链接
- .link-qq: QQ类链接
- .link-wechat: 微信类链接
- .link-wechat-id: 微信号文本
- .link-telegram: Telegram类链接
- .link-divider: 分割线 hr
- .link-headline: 文字标题 h3
- .link-text: 纯文本块 p
- .link-html: 自定义HTML块
- .footer: 页脚（文字居中、小号字体、灰色）

请输出完整可用的CSS代码：";

        $url = rtrim($endpoint, '/') . '/chat/completions';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($response)) {
            return null;
        }

        $data = json_decode($response, true);
        $cssContent = $data['choices'][0]['message']['content'] ?? '';

        if (empty($cssContent)) {
            return null;
        }

        // Clean up markdown code fences if present
        $cssContent = preg_replace('/^```css\s*/i', '', $cssContent);
        $cssContent = preg_replace('/\s*```$/', '', $cssContent);

        // Increment daily counter
        $db->query(
            "INSERT INTO `{$prefix}config` (`key`, `value`, `updated_at`) VALUES (?, ?, ?) ON CONFLICT(`key`) DO UPDATE SET `value` = CAST(COALESCE(CAST(`value` AS INTEGER), 0) + 1 AS TEXT), `updated_at` = ?",
            ['ai_daily_count_' . $today, '1', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]
        );

        return trim($cssContent);
    }

    private function generateCSS($name, $description, $primaryColor)
    {
        // Try real AI API first
        try {
            $css = $this->callAIForCSS($name, $description, $primaryColor);
            if ($css) {
                return "/* AI 生成的主题 */\n\n" . $css;
            }
        } catch (\Exception $e) {
            // Fall through to keyword-based generation
        }

        // Fallback: keyword-based generation
        return $this->generateCSSFallback($name, $description, $primaryColor);
    }

    private function generateCSSFallback($name, $description, $primaryColor)
    {
        $desc = mb_strtolower($name . ' ' . $description);

        // Parse style preferences from description
        $isDark = strpos($desc, '暗') !== false || strpos($desc, 'dark') !== false || strpos($desc, '黑') !== false;
        $isGlass = strpos($desc, '玻璃') !== false || strpos($desc, '毛玻璃') !== false || strpos($desc, 'glass') !== false;
        $isGradient = strpos($desc, '渐变') !== false || strpos($desc, 'gradient') !== false;
        $isRound = strpos($desc, '圆角') !== false || strpos($desc, 'rounded') !== false;

        // Parse color components
        $r = hexdec(substr($primaryColor, 1, 2));
        $g = hexdec(substr($primaryColor, 3, 2));
        $b = hexdec(substr($primaryColor, 5, 2));

        $css = [];

        if ($isDark) {
            $css[] = "/* 暗色主题 */";
            if ($isGradient) {
                $css[] = "body {";
                $css[] = "    background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%);";
                $css[] = "    min-height: 100vh;";
                $css[] = "    color: #e0e0e0;";
                $css[] = "}";
            } else {
                $css[] = "body {";
                $css[] = "    background: #0f0f1a;";
                $css[] = "    color: #e0e0e0;";
                $css[] = "}";
            }
        } else {
            $css[] = "/* 亮色主题 */";
            if ($isGradient) {
                $css[] = "body {";
                $css[] = "    background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 50%, #f5f0ff 100%);";
                $css[] = "    min-height: 100vh;";
                $css[] = "}";
            } else {
                $css[] = "body {";
                $css[] = "    background: #f8fafc;";
                $css[] = "}";
            }
        }

        $radius = $isRound ? '20px' : '12px';
        $css[] = "";
        $css[] = "a, .link {";
        $css[] = "    display: flex;";
        $css[] = "    align-items: center;";
        $css[] = "    gap: 12px;";
        $css[] = "    padding: 14px 18px;";
        $css[] = "    border-radius: $radius;";
        $css[] = "    text-decoration: none;";
        $css[] = "    transition: all 0.2s ease;";

        if ($isGlass) {
            $css[] = "    background: rgba(255,255,255,0.15);";
            $css[] = "    backdrop-filter: blur(12px);";
            $css[] = "    border: 1px solid rgba(255,255,255,0.2);";
            $css[] = "    color: {$primaryColor};";
        } elseif ($isDark) {
            $css[] = "    background: rgba(30,30,50,0.8);";
            $css[] = "    border: 1px solid rgba(255,255,255,0.08);";
            $css[] = "    color: #e0e0e0;";
        } else {
            $css[] = "    background: #ffffff;";
            $css[] = "    border: 1px solid #e2e8f0;";
            $css[] = "    color: #1e293b;";
        }
        $css[] = "}";
        $css[] = "";
        $css[] = "a:hover, .link:hover {";
        $css[] = "    transform: translateY(-2px);";
        $css[] = "    box-shadow: 0 4px 12px rgba({$r},{$g},{$b},0.2);";
        if ($isDark) {
            $css[] = "    border-color: {$primaryColor};";
        }
        $css[] = "}";
        $css[] = "";
        $css[] = ".profile {";
        $css[] = "    text-align: center;";
        $css[] = "    margin-bottom: 24px;";
        $css[] = "}";
        $css[] = "";
        $css[] = ".avatar {";
        $css[] = "    width: 80px;";
        $css[] = "    height: 80px;";
        $css[] = "    border-radius: 50%;";
        $css[] = "    margin: 0 auto 12px;";
        $css[] = "    border: 3px solid {$primaryColor};";
        $css[] = "    box-shadow: 0 0 20px rgba({$r},{$g},{$b},0.3);";
        $css[] = "}";

        return implode("\n", $css);
    }

    private function updateProfile($userId, $data)
    {
        $db = \App\Libraries\Database::getInstance();
        $prefix = \App\Libraries\Config::get('database.prefix', 'lh_');

        $data['updated_at'] = date('Y-m-d H:i:s');
        $set = [];
        $params = [];
        foreach (['company_name', 'industry', 'company_size', 'custom_domain', 'brand_color', 'updated_at'] as $col) {
            if (array_key_exists($col, $data)) {
                $set[] = "`$col` = ?";
                $params[] = $data[$col];
            }
        }
        $params[] = $userId;

        if (!empty($set)) {
            $db->query(
                "UPDATE `{$prefix}enterprise_profiles` SET " . implode(', ', $set) . " WHERE `user_id` = ?",
                $params
            );
        }
    }
}
