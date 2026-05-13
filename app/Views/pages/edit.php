<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑页面 - <?php echo htmlspecialchars($page['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans SC', system-ui, sans-serif; }
        .preview-avatar { width:80px; height:80px; border-radius:50%; margin:0 auto 12px; border:3px solid #3b82f6; box-shadow:0 0 20px rgba(59,130,246,0.3); }
        .preview-link { display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:12px; text-decoration:none; transition:all 0.2s; background:#fff; border:1px solid #e2e8f0; color:#1e293b; margin-bottom:10px; }
        .preview-link:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,0.1); }
        .preview-divider { border:none; border-top:1px solid #e2e8f0; margin:16px 0; }
        .preview-headline { font-size:1.1em; font-weight:600; padding:8px 0; margin-bottom:4px; }
        .preview-text { padding:8px 0; color:#64748b; line-height:1.6; }
    </style>
</head>
<body class="bg-white h-screen flex flex-col">
    <!-- Top bar -->
    <header class="border-b border-gray-200 bg-white px-4 py-2 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-4">
            <a href="/dashboard" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <span class="font-semibold text-gray-900">编辑: <?php echo htmlspecialchars($page['title']); ?></span>
            <span class="text-xs text-gray-400">/<?php echo htmlspecialchars($page['slug']); ?></span>
        </div>
        <div class="flex items-center gap-3">
            <a href="/<?php echo $page['slug']; ?>" target="_blank" class="text-sm text-blue-600 hover:text-blue-700 font-medium">查看页面 &rarr;</a>
            <button onclick="savePage()" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">保存</button>
        </div>
    </header>

    <!-- Main: Editor + Preview -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Left: Editor -->
        <div class="w-1/2 border-r border-gray-200 overflow-y-auto">
            <div class="p-5 space-y-5">
                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
                <?php endif; ?>

                <!-- Page Info -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">页面信息</h3>
                    <form id="page-form" method="POST" action="/pages/<?php echo $page['id']; ?>" onsubmit="event.preventDefault(); savePage();">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">页面标题</label>
                                <input type="text" name="title" id="page-title" value="<?php echo htmlspecialchars($page['title']); ?>"
                                       oninput="updatePreview()"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">个人简介</label>
                                <textarea name="bio" id="page-bio" rows="2" oninput="updatePreview()"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"><?php echo htmlspecialchars($page['bio'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Link List -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">内容块</h3>
                        <button onclick="showAddForm()" class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 font-medium">+ 添加</button>
                    </div>

                    <div id="link-list" class="space-y-2">
                        <?php foreach ($links as $link): ?>
                        <div class="link-item bg-white rounded-lg border border-gray-200 p-3" data-id="<?php echo $link['id']; ?>" data-type="<?php echo htmlspecialchars($link['link_type'] ?? 'link'); ?>" data-title="<?php echo htmlspecialchars($link['title']); ?>" data-url="<?php echo htmlspecialchars($link['url'] ?? ''); ?>">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                    <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 text-gray-500 shrink-0 type-badge"><?php
                                        $typeLabels = ['link'=>'链接','text'=>'文本','headline'=>'标题','divider'=>'分隔线','html'=>'HTML','phone'=>'电话','qq'=>'QQ','wechat'=>'微信','telegram'=>'TG'];
                                        echo $typeLabels[$link['link_type'] ?? 'link'] ?? '链接';
                                    ?></span>
                                    <span class="text-sm text-gray-800 truncate"><?php echo htmlspecialchars($link['title']); ?></span>
                                </div>
                                <div class="flex items-center gap-1 ml-2">
                                    <button onclick="editLink(<?php echo $link['id']; ?>)" class="text-gray-400 hover:text-blue-600 p-1" title="编辑">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form method="POST" action="/links/<?php echo $link['id']; ?>/delete" onsubmit="return confirm('确定删除？')" style="display:inline">
                                        <button type="submit" class="text-gray-400 hover:text-red-600 p-1" title="删除">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($links)): ?>
                            <p class="text-sm text-gray-400 text-center py-6" id="empty-hint">点击 "+ 添加" 添加内容块</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Live Preview -->
        <div class="w-1/2 bg-gray-100 overflow-y-auto flex items-start justify-center p-6">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-lg overflow-hidden" style="min-height:600px;">
                <!-- Preview Header -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 px-6 py-8 text-white text-center">
                    <div class="preview-avatar bg-white/20 mx-auto flex items-center justify-center text-2xl font-bold text-white"><?php echo mb_substr($page['title'], 0, 1); ?></div>
                    <h2 id="preview-title" class="text-xl font-bold"><?php echo htmlspecialchars($page['title']); ?></h2>
                    <p id="preview-bio" class="text-sm text-blue-100 mt-1"><?php echo htmlspecialchars($page['bio'] ?? ''); ?></p>
                </div>
                <!-- Preview Links -->
                <div id="preview-links" class="p-5">
                    <?php foreach ($links as $link): ?>
                        <?php $lt = $link['link_type'] ?? 'link'; ?>
                        <?php if ($lt === 'divider'): ?>
                            <hr class="preview-divider">
                        <?php elseif ($lt === 'headline'): ?>
                            <div class="preview-headline"><?php echo htmlspecialchars($link['title']); ?></div>
                        <?php elseif ($lt === 'text'): ?>
                            <div class="preview-text"><?php echo htmlspecialchars($link['title']); ?></div>
                        <?php elseif ($lt === 'html'): ?>
                            <div class="preview-html"><?php echo $link['url'] ?? ''; ?></div>
                        <?php elseif ($lt === 'phone'): ?>
                            <a href="tel:<?php echo htmlspecialchars($link['url'] ?? ''); ?>" class="preview-link">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span><?php echo htmlspecialchars($link['title']); ?></span>
                            </a>
                        <?php elseif ($lt === 'qq'): ?>
                            <a href="https://wpa.qq.com/msgrd?v=3&uin=<?php echo htmlspecialchars($link['url'] ?? ''); ?>" target="_blank" class="preview-link">
                                <span class="text-xs font-bold text-blue-500">QQ</span>
                                <span><?php echo htmlspecialchars($link['title']); ?></span>
                            </a>
                        <?php elseif ($lt === 'wechat'): ?>
                            <div class="preview-link cursor-pointer" onclick="navigator.clipboard.writeText('<?php echo htmlspecialchars($link['url'] ?? ''); ?>');alert('已复制微信号')">
                                <span class="text-xs font-bold text-green-500">微信</span>
                                <span><?php echo htmlspecialchars($link['title']); ?></span>
                                <span class="ml-auto text-xs text-gray-400"><?php echo htmlspecialchars($link['url'] ?? ''); ?></span>
                            </div>
                        <?php elseif ($lt === 'telegram'): ?>
                            <a href="https://t.me/<?php echo htmlspecialchars($link['url'] ?? ''); ?>" target="_blank" class="preview-link">
                                <span class="text-xs font-bold text-blue-400">TG</span>
                                <span><?php echo htmlspecialchars($link['title']); ?></span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo htmlspecialchars($link['url'] ?? '#'); ?>" target="_blank" class="preview-link">
                                <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                <span><?php echo htmlspecialchars($link['title']); ?></span>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Link Modal -->
    <div id="link-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900" id="modal-title">添加内容块</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="link-form" method="POST" action="/links">
                <input type="hidden" name="page_id" value="<?php echo $page['id']; ?>">
                <input type="hidden" name="_method" id="form-method" value="">
                <input type="hidden" name="link_id" id="form-link-id" value="">

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">类型</label>
                        <select name="link_type" id="link-type" onchange="onTypeChange()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="link">链接</option>
                            <option value="text">文本</option>
                            <option value="headline">标题</option>
                            <option value="divider">分隔线</option>
                            <option value="html">HTML</option>
                            <option value="phone">电话</option>
                            <option value="qq">QQ</option>
                            <option value="wechat">微信</option>
                            <option value="telegram">Telegram</option>
                        </select>
                    </div>
                    <div id="field-title">
                        <label class="block text-sm font-medium text-gray-700 mb-1">标题</label>
                        <input type="text" name="title" id="input-title"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div id="field-url">
                        <label class="block text-sm font-medium text-gray-700 mb-1">链接 / 内容</label>
                        <input type="text" name="url" id="input-url"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="https://example.com">
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeModal()" class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">取消</button>
                    <button type="submit" class="flex-1 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">确认</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function showAddForm() {
        document.getElementById('modal-title').textContent = '添加内容块';
        document.getElementById('form-method').value = '';
        document.getElementById('form-link-id').value = '';
        document.getElementById('link-form').action = '/links';
        document.getElementById('input-title').value = '';
        document.getElementById('input-url').value = '';
        document.getElementById('link-type').value = 'link';
        onTypeChange();
        openModal();
    }

    function editLink(id) {
        var item = document.querySelector('.link-item[data-id="' + id + '"]');
        if (!item) return;
        document.getElementById('modal-title').textContent = '编辑内容块';
        document.getElementById('form-method').value = 'PUT';
        document.getElementById('form-link-id').value = id;
        document.getElementById('link-form').action = '/links/' + id;
        document.getElementById('link-type').value = item.dataset.type;
        document.getElementById('input-title').value = item.dataset.title;
        document.getElementById('input-url').value = item.dataset.url || '';
        onTypeChange();
        openModal();
    }

    function onTypeChange() {
        var type = document.getElementById('link-type').value;
        var titleDiv = document.getElementById('field-title');
        var urlDiv = document.getElementById('field-url');
        var titleLabel = titleDiv.querySelector('label');
        var urlLabel = urlDiv.querySelector('label');

        if (type === 'divider') {
            titleDiv.style.display = 'none';
            urlDiv.style.display = 'none';
        } else if (type === 'text') {
            titleDiv.style.display = 'block';
            titleLabel.textContent = '文本内容';
            urlDiv.style.display = 'none';
        } else if (type === 'headline') {
            titleDiv.style.display = 'block';
            titleLabel.textContent = '标题文字';
            urlDiv.style.display = 'none';
        } else if (type === 'html') {
            titleDiv.style.display = 'none';
            urlDiv.style.display = 'block';
            urlLabel.textContent = 'HTML 代码';
        } else if (type === 'phone') {
            titleDiv.style.display = 'block';
            titleLabel.textContent = '显示名称';
            urlDiv.style.display = 'block';
            urlLabel.textContent = '电话号码';
        } else if (type === 'qq') {
            titleDiv.style.display = 'block';
            titleLabel.textContent = '显示名称';
            urlDiv.style.display = 'block';
            urlLabel.textContent = 'QQ 号码';
        } else if (type === 'wechat') {
            titleDiv.style.display = 'block';
            titleLabel.textContent = '显示名称';
            urlDiv.style.display = 'block';
            urlLabel.textContent = '微信号';
        } else if (type === 'telegram') {
            titleDiv.style.display = 'block';
            titleLabel.textContent = '显示名称';
            urlDiv.style.display = 'block';
            urlLabel.textContent = 'Telegram 用户名';
        } else {
            titleDiv.style.display = 'block';
            titleLabel.textContent = '链接标题';
            urlDiv.style.display = 'block';
            urlLabel.textContent = '链接地址';
        }
    }

    function openModal() {
        document.getElementById('link-modal').classList.remove('hidden');
        document.getElementById('link-modal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('link-modal').classList.add('hidden');
        document.getElementById('link-modal').classList.remove('flex');
    }

    document.getElementById('link-modal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    function updatePreview() {
        document.getElementById('preview-title').textContent = document.getElementById('page-title').value || '未命名';
        document.getElementById('preview-bio').textContent = document.getElementById('page-bio').value || '';
    }

    function savePage() {
        var form = document.getElementById('page-form');
        var data = new FormData(form);
        fetch(form.action, { method: 'POST', body: data })
            .then(function() { location.reload(); });
    }

    // Handle link form submission via modal - allows edit to work via POST
    document.getElementById('link-form').addEventListener('submit', function(e) {
        var method = document.getElementById('form-method').value;
        if (method === 'PUT') {
            e.preventDefault();
            var form = this;
            var data = new FormData(form);
            data.append('_method', 'PUT');
            fetch(form.action, { method: 'POST', body: data })
                .then(function() { location.reload(); });
        }
    });
    </script>
</body>
</html>
