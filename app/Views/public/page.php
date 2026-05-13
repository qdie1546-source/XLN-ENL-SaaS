<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page['title']); ?> - <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></title>
    <link rel="stylesheet" href="<?php echo $theme['style']; ?>">
    <?php if (!empty($page['custom_css'])): ?>
    <style><?php echo $page['custom_css']; ?></style>
    <?php endif; ?>
</head>
<body>
    <div class="container">
        <div class="profile">
            <div class="avatar">👤</div>
            <h1 class="name"><?php echo htmlspecialchars($page['title']); ?></h1>
            <?php if ($page['bio']): ?>
                <p class="bio"><?php echo htmlspecialchars($page['bio']); ?></p>
            <?php endif; ?>
        </div>

        <?php if (empty($links)): ?>
            <div class="text-center py-8 text-gray-500">暂无内容</div>
        <?php else: ?>
            <div class="links">
                <?php foreach ($links as $link): ?>
                    <?php if (!($link['is_active'] ?? 1)) continue; ?>
                    <?php $lt = $link['link_type'] ?? 'link'; ?>
                    <?php if ($lt === 'divider'): ?>
                        <hr class="link-divider">
                    <?php elseif ($lt === 'headline'): ?>
                        <h3 class="link-headline"><?php echo htmlspecialchars($link['title']); ?></h3>
                    <?php elseif ($lt === 'text'): ?>
                        <p class="link-text"><?php echo nl2br(htmlspecialchars($link['title'])); ?></p>
                    <?php elseif ($lt === 'html'): ?>
                        <div class="link-html"><?php echo $link['url'] ?? ''; ?></div>
                    <?php elseif ($lt === 'phone'): ?>
                        <a href="tel:<?php echo htmlspecialchars($link['url'] ?? ''); ?>" class="link link-phone">
                            <span class="link-icon">📞</span>
                            <span><?php echo htmlspecialchars($link['title']); ?></span>
                        </a>
                    <?php elseif ($lt === 'qq'): ?>
                        <a href="https://wpa.qq.com/msgrd?v=3&uin=<?php echo htmlspecialchars($link['url'] ?? ''); ?>" target="_blank" class="link link-qq">
                            <span class="link-icon">💬</span>
                            <span><?php echo htmlspecialchars($link['title']); ?></span>
                        </a>
                    <?php elseif ($lt === 'wechat'): ?>
                        <div class="link link-wechat" onclick="copyWechat('<?php echo htmlspecialchars($link['url'] ?? ''); ?>')">
                            <span class="link-icon">💚</span>
                            <span><?php echo htmlspecialchars($link['title']); ?></span>
                            <span class="link-wechat-id"><?php echo htmlspecialchars($link['url'] ?? ''); ?></span>
                        </div>
                    <?php elseif ($lt === 'telegram'): ?>
                        <a href="https://t.me/<?php echo htmlspecialchars($link['url'] ?? ''); ?>" target="_blank" class="link link-telegram">
                            <span class="link-icon">✈️</span>
                            <span><?php echo htmlspecialchars($link['title']); ?></span>
                        </a>
                    <?php else: ?>
                        <a href="/track/link/<?php echo $link['id']; ?>" target="_blank" class="link">
                            <?php echo htmlspecialchars($link['title']); ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="footer">
            <a href="/">Powered by <?php echo htmlspecialchars(\ConfigHelper::siteName()); ?></a>
        </div>
    </div>

    <script>
    function copyWechat(id) {
        navigator.clipboard.writeText(id).then(function() {
            alert('微信号已复制: ' + id);
        });
    }
    </script>
</body>
</html>
