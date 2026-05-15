<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - 页面未找到</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-code {
            font-size: 120px;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .error-title {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .error-description {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: swing 2s ease-in-out infinite;
        }

        @keyframes swing {
            0%, 100% {
                transform: rotate(0deg);
            }
            25% {
                transform: rotate(-5deg);
            }
            75% {
                transform: rotate(5deg);
            }
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .btn {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.6);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 2px solid #e0e0e0;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
            border-color: #d0d0d0;
            transform: translateY(-3px);
        }

        .search-box {
            margin-top: 40px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 15px;
            border: 2px solid #eee;
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-hint {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }

        .footer-links {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .status-code {
            display: inline-block;
            padding: 10px 20px;
            background: #f0f0f0;
            border-radius: 8px;
            font-family: 'Monaco', 'Menlo', monospace;
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* 响应式设计 */
        @media (max-width: 768px) {
            .container {
                padding: 40px 30px;
            }

            .error-code {
                font-size: 80px;
            }

            .error-title {
                font-size: 24px;
            }

            .error-description {
                font-size: 14px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .footer-links {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: center;
            }

            .footer-links a {
                margin: 0;
            }
        }

        /* 深色模式支持 */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
            }

            .container {
                background: rgba(30, 30, 30, 0.95);
            }

            .error-title {
                color: #f0f0f0;
            }

            .error-description {
                color: #b0b0b0;
            }

            .status-code {
                background: #444;
                color: #ccc;
            }

            .btn-secondary {
                background: #333;
                color: #f0f0f0;
                border-color: #555;
            }

            .btn-secondary:hover {
                background: #444;
                border-color: #666;
            }

            .search-box {
                background: #2a2a2a;
                border-color: #444;
            }

            .search-box input {
                background: #333;
                border-color: #555;
                color: #f0f0f0;
            }

            .search-box input::placeholder {
                color: #888;
            }

            .search-box input:focus {
                border-color: #667eea;
            }

            .footer-links {
                border-top-color: #444;
            }

            .search-hint {
                color: #888;
            }
        }

        /* 动画优化 */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">🚀</div>
        
        <div class="error-code">404</div>
        
        <div class="status-code">HTTP 404 Not Found</div>
        
        <h1 class="error-title">哎呀！页面未找到</h1>
        
        <p class="error-description">
            很抱歉，您访问的页面似乎不存在或已被删除。<br>
            请检查 URL 是否正确，或尝试使用下面的导航选项。
        </p>

        <div class="button-group">
            <a href="/" class="btn btn-primary">返回首页</a>
            <a href="javascript:history.back()" class="btn btn-secondary">返回上一页</a>
        </div>

        <div class="search-box">
            <input type="text" placeholder="搜索你想要的内容..." id="searchInput">
            <div class="search-hint">输入关键词并按 Enter 搜索</div>
        </div>

        <div class="footer-links">
            <a href="/">首页</a>
            <a href="/login">登录</a>
            <a href="/register">注册</a>
            <a href="javascript:void(0)" onclick="contactSupport()">联系支持</a>
        </div>
    </div>

    <script>
        // 搜索功能
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                // 你可以修改这里的搜索逻辑
                window.location.href = '/?search=' + encodeURIComponent(this.value);
            }
        });

        // 联系支持
        function contactSupport() {
            alert('感谢您的反馈！\n\n如需帮助，请发送邮件至：support@linkhub.com');
        }

        // 添加页面加载动画
        window.addEventListener('load', function() {
            document.querySelector('.container').style.animation = 'slideUp 0.6s ease-out';
        });
    </script>
</body>
</html>
