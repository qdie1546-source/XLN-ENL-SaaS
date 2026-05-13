<?php

define('BASE_PATH', dirname(__DIR__));

if (file_exists(BASE_PATH . '/.env')) {
    header('Location: /');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'install') {
        $siteName = $_POST['site_name'] ?? 'LinkHub';
        $siteUrl = $_POST['site_url'] ?? 'http://localhost';
        $adminEmail = $_POST['admin_email'] ?? '';
        $adminPassword = $_POST['admin_password'] ?? '';
        
        $dbHost = $_POST['db_host'] ?? 'localhost';
        $dbPort = $_POST['db_port'] ?? 3306;
        $dbName = $_POST['db_name'] ?? '';
        $dbUser = $_POST['db_user'] ?? '';
        $dbPass = $_POST['db_pass'] ?? '';
        $dbPrefix = $_POST['db_prefix'] ?? 'lh_';
        
        if (empty($dbName)) {
            $errors[] = '请填写数据库名称';
        }
        if (empty($adminEmail)) {
            $errors[] = '请填写管理员邮箱';
        }
        if (strlen($adminPassword) < 8) {
            $errors[] = '管理员密码至少 8 个字符';
        }
        
        if (empty($errors)) {
            try {
                $dsn = "mysql:host=$dbHost;port=$dbPort;charset=utf8mb4";
                $pdo = new PDO($dsn, $dbUser, $dbPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
                
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `$dbName`");
                
                $envContent = "APP_NAME=$siteName\n";
                $envContent .= "APP_URL=$siteUrl\n";
                $envContent .= "APP_ENV=production\n";
                $envContent .= "APP_DEBUG=false\n\n";
                $envContent .= "DB_HOST=$dbHost\n";
                $envContent .= "DB_PORT=$dbPort\n";
                $envContent .= "DB_NAME=$dbName\n";
                $envContent .= "DB_USER=$dbUser\n";
                $envContent .= "DB_PASS=$dbPass\n";
                $envContent .= "DB_PREFIX=$dbPrefix\n\n";
                $envContent .= "AI_PROVIDER=openai\n";
                $envContent .= "AI_API_KEY=\n";
                $envContent .= "AI_API_ENDPOINT=\n";
                $envContent .= "AI_MODEL=gpt-3.5-turbo\n";
                $envContent .= "AI_DAILY_LIMIT=100\n";
                file_put_contents(BASE_PATH . '/.env', $envContent);
                
                $migration = require BASE_PATH . '/database/migrations/001_initial_schema.php';
                $migration = str_replace('{prefix}', $dbPrefix, $migration);
                
                $statements = explode(';', $migration);
                foreach ($statements as $statement) {
                    $statement = trim($statement);
                    if (!empty($statement)) {
                        $pdo->exec($statement);
                    }
                }
                
                $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO `{$dbPrefix}users` (`email`, `password`, `user_type`, `status`, `email_verified`) VALUES (?, ?, 'enterprise', 'active', 1)");
                $stmt->execute([$adminEmail, $hashedPassword]);
                $userId = $pdo->lastInsertId();
                
                $stmt = $pdo->prepare("INSERT INTO `{$dbPrefix}themes` (`name`, `slug`, `css_content`, `is_free`, `is_active`) VALUES 
                    ('Minimal', 'minimal', ':root { --primary: #3b82f6; --bg: #ffffff; --text: #1f2937; }', 1, 1),
                    ('Gradient', 'gradient', ':root { --primary: #6366f1; --bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%); --text: #ffffff; }', 1, 1),
                    ('Card', 'card', ':root { --primary: #8b5cf6; --bg: #f8fafc; --text: #1e293b; }', 1, 1),
                    ('Dark', 'dark', ':root { --primary: #60a5fa; --bg: #0f172a; --text: #e2e8f0; }', 1, 1)
                ");
                $stmt->execute();
                
                $installKey = bin2hex(random_bytes(32));
                $stmt = $pdo->prepare("INSERT INTO `{$dbPrefix}install_config` (`install_key`, `site_name`, `site_url`, `admin_email`, `admin_password`, `db_host`, `db_port`, `db_name`, `db_user`, `db_password`, `db_prefix`, `is_installed`, `installed_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())");
                $stmt->execute([$installKey, $siteName, $siteUrl, $adminEmail, $hashedPassword, $dbHost, $dbPort, $dbName, $dbUser, $dbPass, $dbPrefix]);
                
                $success = true;
            } catch (PDOException $e) {
                if (file_exists(BASE_PATH . '/.env')) {
                    unlink(BASE_PATH . '/.env');
                }
                $errors[] = '安装失败: ' . $e->getMessage();
            }
        }
    }
}

function checkPHPVersion()
{
    return version_compare(PHP_VERSION, '7.4.0', '>=');
}

function checkExtension($ext)
{
    return extension_loaded($ext);
}

function checkWritable($dir)
{
    $fullPath = BASE_PATH . '/' . ltrim($dir, '/');
    if (!file_exists($fullPath)) {
        return mkdir($fullPath, 0755, true);
    }
    return is_writable($fullPath);
}

$phpOk = checkPHPVersion();
$pdoOk = checkExtension('pdo') && checkExtension('pdo_mysql');
$gdOk = checkExtension('gd');
$mbstringOk = checkExtension('mbstring');
$configWritable = checkWritable('config') && checkWritable('');

$allOk = $phpOk && $pdoOk && $gdOk && $mbstringOk && $configWritable;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkHub 安装向导</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans SC', system-ui, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-6 py-4">
            <div class="flex items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-xl font-semibold text-slate-900">LinkHub</h1>
                        <p class="text-sm text-slate-500">社交链接聚合平台</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-10">
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-slate-900">安装向导</h2>
                <span class="text-sm text-slate-500" id="progress-text">步骤 1 / 4</span>
            </div>
            
            <div class="w-full bg-slate-200 rounded-full h-2 mb-6">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" id="progress-bar" style="width: <?php echo $success ? '100' : '25'; ?>%;"></div>
            </div>
            
            <div class="grid grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="w-10 h-10 mx-auto rounded-xl flex items-center justify-center text-sm font-medium <?php echo $success ? 'bg-green-600 text-white' : 'bg-blue-600 text-white'; ?>" id="step1-indicator">
                        <?php echo $success ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' : '1'; ?>
                    </div>
                    <p class="mt-2 text-sm font-medium text-slate-900">环境检测</p>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 mx-auto rounded-xl flex items-center justify-center text-sm font-medium <?php echo $success ? 'bg-green-600 text-white' : 'bg-slate-200 text-slate-500'; ?>" id="step2-indicator">
                        <?php echo $success ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' : '2'; ?>
                    </div>
                    <p class="mt-2 text-sm font-medium text-slate-500" id="step2-label">配置信息</p>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 mx-auto rounded-xl flex items-center justify-center text-sm font-medium <?php echo $success ? 'bg-green-600 text-white' : 'bg-slate-200 text-slate-500'; ?>" id="step3-indicator">
                        <?php echo $success ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' : '3'; ?>
                    </div>
                    <p class="mt-2 text-sm font-medium text-slate-500" id="step3-label">执行安装</p>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 mx-auto rounded-xl flex items-center justify-center text-sm font-medium <?php echo $success ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500'; ?>" id="step4-indicator">4</div>
                    <p class="mt-2 text-sm font-medium text-slate-500" id="step4-label">完成</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div id="step1" class="step-content <?php echo $success ? 'hidden' : 'p-8'; ?>">
                <h3 class="text-lg font-semibold text-slate-900 mb-6">环境检测</h3>
                <p class="text-slate-600 mb-6">请确认以下系统要求已满足</p>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-slate-900">PHP 版本</p>
                                <p class="text-xs text-slate-500">需要 7.4 或更高</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-slate-600 mr-3"><?php echo PHP_VERSION; ?></span>
                            <?php if ($phpOk): ?>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <?php else: ?>
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-slate-900">MySQL 扩展</p>
                                <p class="text-xs text-slate-500">PDO 驱动支持</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-slate-600 mr-3"><?php echo $pdoOk ? '已启用' : '未启用'; ?></span>
                            <?php if ($pdoOk): ?>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <?php else: ?>
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-slate-900">GD 扩展</p>
                                <p class="text-xs text-slate-500">图片处理支持</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-slate-600 mr-3"><?php echo $gdOk ? '已启用' : '未启用'; ?></span>
                            <?php if ($gdOk): ?>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <?php else: ?>
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-slate-900">mbstring 扩展</p>
                                <p class="text-xs text-slate-500">多字节字符串支持</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-slate-600 mr-3"><?php echo $mbstringOk ? '已启用' : '未启用'; ?></span>
                            <?php if ($mbstringOk): ?>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <?php else: ?>
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-slate-900">config 目录</p>
                                <p class="text-xs text-slate-500">需要写入权限</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-slate-600 mr-3"><?php echo $configWritable ? '可写' : '不可写'; ?></span>
                            <?php if ($configWritable): ?>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <?php else: ?>
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($errors)): ?>
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <?php foreach ($errors as $error): ?>
                    <p class="text-sm text-red-700">✗ <?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($allOk): ?>
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <p class="text-sm text-green-700 font-medium">✓ 所有环境要求已满足</p>
                </div>
                <div class="mt-8 flex justify-end">
                    <button onclick="showStep(2)" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium shadow-sm transition-colors flex items-center">
                        开始配置
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
                <?php else: ?>
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-sm text-red-700 font-medium">✗ 请修复上述问题后继续</p>
                </div>
                <?php endif; ?>
            </div>

            <div id="step2" class="step-content <?php echo $success ? 'hidden' : 'hidden p-8'; ?>">
                <h3 class="text-lg font-semibold text-slate-900 mb-6">配置信息</h3>
                <p class="text-slate-600 mb-6">请填写以下配置信息</p>

                <form method="POST" id="install-form">
                    <input type="hidden" name="action" value="install">
                    <div class="mb-8">
                        <h4 class="text-sm font-medium text-slate-700 mb-4">站点配置</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">站点名称</label>
                                <input type="text" name="site_name" value="LinkHub" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">站点 URL</label>
                                <input type="url" name="site_url" value="<?php echo isset($_SERVER['HTTPS']) ? 'https' : 'http'; ?>://<?php echo $_SERVER['HTTP_HOST']; ?>" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-sm font-medium text-slate-700 mb-4">管理员账号</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">管理员邮箱</label>
                                <input type="email" name="admin_email" value="admin@example.com" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">管理员密码</label>
                                <input type="password" name="admin_password" placeholder="至少8位字符" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-sm font-medium text-slate-700 mb-4">数据库配置</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">数据库主机</label>
                                <input type="text" name="db_host" value="localhost" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">数据库端口</label>
                                <input type="text" name="db_port" value="3306" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">数据库名称</label>
                                <input type="text" name="db_name" value="linkhub_db" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">数据表前缀</label>
                                <input type="text" name="db_prefix" value="lh_" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">数据库用户名</label>
                                <input type="text" name="db_user" value="root" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">数据库密码</label>
                                <input type="password" name="db_pass" placeholder="请输入密码" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" onclick="showStep(1)" class="text-slate-600 hover:text-slate-800 px-6 py-3 rounded-xl font-medium transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            上一步
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium shadow-sm transition-colors flex items-center">
                            执行安装
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <div id="step4" class="step-content <?php echo $success ? 'p-8' : 'hidden'; ?>">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">安装成功！</h3>
                    <p class="text-slate-600 mb-8">LinkHub 已成功安装并可以开始使用</p>

                    <div class="bg-slate-50 rounded-xl p-6 mb-8 text-left">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">管理后台地址</p>
                                <a href="/admin" class="text-blue-600 hover:text-blue-700 font-medium">/admin</a>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">访问首页</p>
                                <a href="/" class="text-blue-600 hover:text-blue-700 font-medium">点击访问</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8 text-left">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-amber-800">安全提示</p>
                                <p class="text-sm text-amber-700 mt-1">请立即删除 <code class="bg-amber-100 px-2 py-0.5 rounded">install.php</code> 文件，防止他人重新运行安装程序。</p>
                            </div>
                        </div>
                    </div>

                    <a href="/admin" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium shadow-sm transition-colors">
                        进入管理后台
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="mt-12 py-6 border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <p class="text-sm text-slate-500">© 2026 LinkHub. All rights reserved.</p>
        </div>
    </footer>

    <script>
        let currentStep = <?php echo $success ? 4 : 1; ?>;

        function updateProgress(step) {
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            
            const progress = step * 25;
            progressBar.style.width = progress + '%';
            progressText.textContent = '步骤 ' + step + ' / 4';
            
            for (let i = 1; i <= 4; i++) {
                const indicator = document.getElementById('step' + i + '-indicator');
                const label = document.getElementById('step' + i + '-label') || indicator.parentElement.querySelector('p');
                
                if (i < step) {
                    indicator.classList.remove('bg-slate-200', 'text-slate-500');
                    indicator.classList.add('bg-green-600', 'text-white');
                    if (!indicator.querySelector('svg')) {
                        indicator.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                    }
                    if (label) {
                        label.classList.remove('text-slate-500');
                        label.classList.add('text-slate-900');
                    }
                } else if (i === step) {
                    indicator.classList.remove('bg-slate-200', 'text-slate-500', 'bg-green-600');
                    indicator.classList.add('bg-blue-600', 'text-white');
                    if (!/^\d$/.test(indicator.textContent.trim())) {
                        indicator.textContent = i;
                    }
                    if (label) {
                        label.classList.remove('text-slate-500');
                        label.classList.add('text-slate-900');
                    }
                } else {
                    indicator.classList.remove('bg-blue-600', 'text-white', 'bg-green-600');
                    indicator.classList.add('bg-slate-200', 'text-slate-500');
                    if (indicator.querySelector('svg')) {
                        indicator.textContent = i;
                    }
                    if (label) {
                        label.classList.remove('text-slate-900');
                        label.classList.add('text-slate-500');
                    }
                }
            }
        }

        function showStep(step) {
            currentStep = step;
            const contents = document.querySelectorAll('.step-content');
            contents.forEach((content, index) => {
                if (index + 1 === step) {
                    content.classList.remove('hidden');
                    if (content.classList.contains('p-8')) {
                        content.classList.remove('p-8');
                        content.classList.add('p-8');
                    }
                } else {
                    content.classList.add('hidden');
                }
            });
            updateProgress(step);
        }

        updateProgress(currentStep);
    </script>
</body>
</html>
