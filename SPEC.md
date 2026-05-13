# LinkHub - 社交链接聚合 SaaS 平台技术规格书

## 项目概述

### 项目定位
一个类似 Linktree 的社交链接聚合平台，允许用户创建个人主页，汇集多个社交媒体链接。支持 AI 生成页面设计、完整的数据统计、企业品牌定制等功能。

### 核心价值
- **永久免费 + 付费增值** 的商业模式
- **AI 赋能** - 让用户通过文字描述自动生成专业级个人主页
- **企业级功能** - 支持自定义域名、企业品牌、独立管理后台

### 目标用户
- 社交媒体创作者、KOL
- 小型企业、个体创业者
- 电商卖家、音乐人、摄影师
- 需要统一展示多个链接的个人/企业

---

## 一、技术架构

### 1.1 技术栈

**后端**
- PHP 7.4+ / 8.0
- 原生 MVC 架构（不使用重型框架）
- Composer 依赖管理

**数据库**
- MySQL 5.7+ / MariaDB 10.3+

**前端**
- HTML5 + CSS3 + Vanilla JS
- Alpine.js（响应式交互）
- Tailwind CSS（样式框架）

**部署环境**
- 宝塔面板（Nginx/Apache）
- 任意支持 PHP 的虚拟主机

### 1.2 项目目录结构

```
linkhub/
├── app/
│   ├── Controllers/           # 控制器
│   │   ├── AuthController.php
│   │   ├── PageController.php
│   │   ├── LinkController.php
│   │   ├── ThemeController.php
│   │   ├── StatsController.php
│   │   ├── AIController.php
│   │   └── AdminController.php
│   ├── Models/                 # 数据模型
│   │   ├── User.php
│   │   ├── Page.php
│   │   ├── Link.php
│   │   ├── Theme.php
│   │   ├── EnterpriseProfile.php
│   │   └── Statistic.php
│   ├── Views/                  # 视图
│   │   ├── auth/
│   │   ├── user/
│   │   ├── admin/
│   │   └── templates/         # 页面主题模板
│   ├── Services/               # 业务逻辑
│   │   ├── AuthService.php
│   │   ├── PageService.php
│   │   ├── AIService.php
│   │   ├── StatsService.php
│   │   └── ThemeService.php
│   ├── Libraries/              # 公共类库
│   │   ├── Database.php
│   │   ├── Session.php
│   │   ├── Validator.php
│   │   ├── Response.php
│   │   └── Config.php
│   └── Helpers/                 # 辅助函数
│       ├── url.php
│       ├── session.php
│       └── utils.php
├── config/
│   ├── app.php                # 应用配置
│   ├── database.php           # 数据库配置
│   ├── ai.php                 # AI配置
│   └── themes.php             # 主题配置
├── database/
│   └── migrations/            # 数据库迁移脚本
├── public/
│   ├── index.php             # 入口文件
│   ├── install.php           # 安装程序
│   ├── .htaccess             # Apache重写规则
│   └── assets/              # 前端资源
│       ├── css/
│       ├── js/
│       └── images/
├── routes/
│   └── web.php               # 路由定义
├── templates/                # 页面主题模板
│   ├── minimal/
│   ├── gradient/
│   ├── card/
│   └── ...
├── composer.json
├── .env.example
└── README.md
```

---

## 二、数据库设计

### 核心表结构

#### 1. 用户表（users）
```sql
CREATE TABLE users (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    phone           VARCHAR(20) NULL,
    user_type       ENUM('individual', 'enterprise') DEFAULT 'individual',
    status          ENUM('active', 'suspended', 'inactive') DEFAULT 'active',
    email_verified  TINYINT(1) DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 2. 企业信息表（enterprise_profiles）
```sql
CREATE TABLE enterprise_profiles (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED NOT NULL UNIQUE,
    company_name    VARCHAR(255) NOT NULL,
    logo_url        VARCHAR(500) NULL,
    custom_domain   VARCHAR(255) NULL UNIQUE,
    brand_color     VARCHAR(7) DEFAULT '#000000',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### 3. 页面配置表（pages）
```sql
CREATE TABLE pages (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED NOT NULL,
    slug            VARCHAR(50) NOT NULL UNIQUE,
    title           VARCHAR(255) DEFAULT 'My Links',
    description     TEXT NULL,
    avatar_url      VARCHAR(500) NULL,
    bio             TEXT NULL,
    theme_id        INT UNSIGNED DEFAULT 1,
    custom_css      TEXT NULL,
    custom_bg       VARCHAR(500) NULL,
    seo_title       VARCHAR(255) NULL,
    seo_description TEXT NULL,
    is_published    TINYINT(1) DEFAULT 1,
    view_count      INT UNSIGNED DEFAULT 0,
    click_count     INT UNSIGNED DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### 4. 链接表（links）
```sql
CREATE TABLE links (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id         BIGINT UNSIGNED NOT NULL,
    title           VARCHAR(255) NOT NULL,
    url             VARCHAR(500) NOT NULL,
    icon            VARCHAR(100) NULL,
    position        INT UNSIGNED DEFAULT 0,
    link_type       ENUM('url', 'image', 'video', 'audio', 'payment', 'map') DEFAULT 'url',
    is_active       TINYINT(1) DEFAULT 1,
    click_count     INT UNSIGNED DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
);
```

#### 5. 主题表（themes）
```sql
CREATE TABLE themes (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    slug            VARCHAR(50) NOT NULL UNIQUE,
    preview_image   VARCHAR(500) NULL,
    css_content     TEXT NOT NULL,
    is_free         TINYINT(1) DEFAULT 1,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 6. 访问统计表（statistics）
```sql
CREATE TABLE statistics (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    link_id         BIGINT UNSIGNED NULL,
    page_id         BIGINT UNSIGNED NOT NULL,
    ip_address      VARCHAR(45) NOT NULL,
    country         VARCHAR(100) NULL,
    city            VARCHAR(100) NULL,
    device_type     ENUM('desktop', 'mobile', 'tablet') DEFAULT 'desktop',
    browser         VARCHAR(100) NULL,
    os              VARCHAR(100) NULL,
    referer         VARCHAR(500) NULL,
    clicked_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (link_id) REFERENCES links(id) ON DELETE SET NULL,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
);
```

#### 7. AI配置表（ai_settings）
```sql
CREATE TABLE ai_settings (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider        ENUM('openai', 'claude', 'zhipu', 'custom') DEFAULT 'openai',
    api_key         VARCHAR(500) NOT NULL,
    api_endpoint    VARCHAR(500) NULL,
    model           VARCHAR(100) DEFAULT 'gpt-3.5-turbo',
    is_active       TINYINT(1) DEFAULT 1,
    daily_limit     INT UNSIGNED DEFAULT 100,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 8. AI使用记录表（ai_usage_logs）
```sql
CREATE TABLE ai_usage_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED NOT NULL,
    prompt_tokens   INT UNSIGNED DEFAULT 0,
    completion_tokens INT UNSIGNED DEFAULT 0,
    total_tokens    INT UNSIGNED DEFAULT 0,
    cost            DECIMAL(10, 6) DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### 9. 安装配置表（install_config）
```sql
CREATE TABLE install_config (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    install_key     VARCHAR(64) NOT NULL UNIQUE,
    site_name       VARCHAR(255) DEFAULT 'LinkHub',
    site_url        VARCHAR(255) NOT NULL,
    admin_email     VARCHAR(255) NOT NULL,
    admin_password  VARCHAR(255) NOT NULL,
    db_host         VARCHAR(255) NOT NULL,
    db_port         INT UNSIGNED DEFAULT 3306,
    db_name         VARCHAR(255) NOT NULL,
    db_user         VARCHAR(255) NOT NULL,
    db_password     VARCHAR(255) NOT NULL,
    db_prefix       VARCHAR(50) DEFAULT 'lh_',
    is_installed    TINYINT(1) DEFAULT 0,
    installed_at    TIMESTAMP NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 三、路由设计

### 3.1 公开路由
```
GET  /{username}              # 访问用户主页
GET  /{username}/qr           # 生成二维码
GET  /install                 # 安装向导
POST /install/process         # 执行安装
```

### 3.2 认证路由
```
GET  /login                   # 登录页
POST /login                   # 执行登录
GET  /register                # 注册页
POST /register                # 执行注册
POST /logout                  # 退出登录
GET  /forgot-password         # 忘记密码
POST /forgot-password         # 发送重置邮件
```

### 3.3 用户后台路由
```
GET  /dashboard               # 控制台首页
GET  /profile                 # 个人资料
POST /profile                 # 更新资料

# 页面管理
GET  /pages                   # 页面列表
GET  /pages/create            # 创建页面
POST /pages                   # 保存页面
GET  /pages/{id}/edit         # 编辑页面
PUT  /pages/{id}              # 更新页面
DELETE /pages/{id}            # 删除页面

# 链接管理
GET  /pages/{id}/links        # 链接列表
POST /links                   # 添加链接
PUT  /links/{id}              # 更新链接
DELETE /links/{id}            # 删除链接
PUT  /links/reorder           # 排序链接

# 主题管理
GET  /themes                  # 主题市场
GET  /themes/{id}/preview     # 主题预览
POST /themes/{id}/apply       # 应用主题

# 统计分析
GET  /analytics               # 数据分析
GET  /analytics/links         # 链接点击统计
GET  /analytics/visitors      # 访客分析

# AI功能
GET  /ai/generate             # AI生成页面
POST /ai/generate             # 执行AI生成
GET  /ai/history               # AI使用记录

# 企业功能
GET  /enterprise               # 企业设置
POST /enterprise              # 保存企业设置
POST /enterprise/domain        # 绑定域名
```

### 3.4 管理后台路由
```
GET  /admin                   # 管理后台首页
GET  /admin/users             # 用户管理
GET  /admin/users/{id}         # 用户详情
PUT  /admin/users/{id}/status  # 修改用户状态
DELETE /admin/users/{id}       # 删除用户

GET  /admin/pages              # 页面管理
GET  /admin/themes             # 主题管理
POST /admin/themes             # 创建主题
PUT  /admin/themes/{id}        # 更新主题

GET  /admin/ai-config          # AI配置
POST /admin/ai-config          # 保存AI配置

GET  /admin/settings           # 站点设置
POST /admin/settings           # 保存站点设置

GET  /admin/stats              # 平台统计
```

---

## 四、核心功能模块

### 4.1 用户系统

**功能列表**：
- 邮箱注册/登录
- 可选手机号绑定
- 邮箱验证
- 找回密码
- 用户类型区分（个人/企业）

**权限层级**：
```
普通用户
├── 创建/管理自己的页面
├── 添加链接（免费版限5个）
├── 使用免费主题
├── 查看基础统计
└── 使用AI生成（有限额度）

企业用户（额外权限）
├── 无限链接数量
├── 自定义CSS
├── 绑定独立域名
├── 移除"Powered by"标识
├── 自定义企业品牌
├── 高级数据统计
└── AI无限使用
```

### 4.2 页面系统

**页面配置**：
- 个性化 slug（URL路径）
- 头像上传
- 昵称、简介
- SEO 设置（标题、描述）
- 主题选择
- 自定义 CSS
- 发布/下架控制

**访问控制**：
- 公开访问
- 密码保护（可选）

### 4.3 链接系统

**链接类型**：
- `url` - 标准链接
- `image` - 图片链接
- `video` - 视频链接
- `audio` - 音频链接
- `payment` - 支付按钮
- `map` - 地图位置

**链接属性**：
- 标题、URL、图标
- 排序位置
- 启用/禁用状态
- 点击统计

### 4.4 主题系统

**内置免费主题（5-8套）**：
- Minimal - 极简风格
- Gradient - 渐变背景
- Card - 卡片布局
- Dark - 暗色主题
- Vintage - 复古风格

**主题结构**：
```
templates/minimal/
├── config.json          # 主题配置
├── template.html        # 模板文件
├── styles.css           # 样式文件
├── preview.png          # 预览图
└── thumbnail.png        # 缩略图
```

### 4.5 AI 生成系统

**工作流程**：
```
用户输入描述
    ↓
前端 → POST /ai/generate
    ↓
后端 AIService
    ↓
调用 AI API（OpenAI/Claude/智谱）
    ↓
AI 返回页面配置 JSON
    ↓
前端预览 → 用户确认
    ↓
保存到 pages 表
```

**AI 提示词设计**：
```php
$systemPrompt = "你是一个专业的网页设计师。根据用户的描述，生成一个个性化的页面配置。
返回JSON格式：
{
    'theme': 'minimal|gradient|card|...',
    'primary_color': '#hex',
    'background': '#hex或图片URL',
    'font': '字体名称',
    'style': '描述风格',
    'suggested_links': ['链接类型数组']
}";

$userPrompt = "用户描述：{$userInput}";
```

**AI 配置（后台可管理）**：
- API Provider 选择（OpenAI/Claude/Zhipu/自定义）
- API Key
- API Endpoint
- 模型选择
- 每日调用限制
- 使用量统计

### 4.6 统计分析系统

**追踪维度**：
- 访问时间
- IP 地址
- 地理位置（国家/城市）
- 设备类型（桌面/移动/平板）
- 浏览器
- 操作系统
- 来源页面（Referer）

**统计指标**：
- 页面总访问量
- 链接总点击量
- 单个链接点击量
- 访客趋势图
- 地域分布图
- 设备分布图

**数据处理**：
```php
// 访客追踪中间件
class TrackerMiddleware {
    public function handle($request, $closure) {
        $stats = [
            'ip' => get_client_ip(),
            'country' => get_country_by_ip($ip),
            'device' => detect_device(),
            'browser' => get_browser_name(),
            'os' => get_os_name(),
            'referer' => $_SERVER['HTTP_REFERER'] ?? null,
            'visited_at' => date('Y-m-d H:i:s')
        ];

        // 异步写入数据库（避免阻塞）
        queue_write('statistics', $stats);

        return $closure($request);
    }
}
```

### 4.7 安装向导系统

**安装流程**：
```
Step 1: 环境检测
├── PHP 版本检查（≥7.4）
├── 必需扩展检查（PDO、mbstring、gd等）
├── 目录权限检查（writable目录）
└── MySQL 连接检测
    ↓
Step 2: 配置信息
├── 站点名称
├── 管理员邮箱
├── 管理员密码
├── 数据库信息（host、port、name、user、password）
└── 数据表前缀
    ↓
Step 3: 安装进度
├── 创建配置文件（config/app.php）
├── 创建数据库连接配置
├── 执行数据库迁移
├── 导入初始主题数据
├── 创建管理员账号
└── 完成安装
    ↓
Step 4: 完成
├── 显示成功信息
├── 进入管理后台
└── 删除 install.php
```

**安全机制**：
- 安装完成后自动删除 install.php
- 配置文件加密存储敏感信息
- 安装密钥验证

---

## 五、系统架构图

```
┌─────────────────────────────────────────────────────────────┐
│                        用户端                                │
│  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐       │
│  │  主页   │  │ 注册/登录 │  │ 用户后台 │  │ AI生成  │       │
│  └────┬────┘  └────┬────┘  └────┬────┘  └────┬────┘       │
└───────┼────────────┼────────────┼────────────┼─────────────┘
        │            │            │            │
        └────────────┴─────┬──────┴────────────┘
                          │
                    ┌─────▼─────┐
                    │  Router   │
                    └─────┬─────┘
                          │
        ┌─────────────────┼─────────────────┐
        │                 │                 │
   ┌────▼────┐      ┌────▼────┐      ┌────▼────┐
   │ Auth    │      │ Page    │      │ AI      │
   │Controller│      │Controller│      │Controller│
   └────┬────┘      └────┬────┘      └────┬────┘
        │                 │                 │
   ┌────▼────┐      ┌────▼────┐      ┌────▼────┐
   │ Auth    │      │ Page    │      │ AI      │
   │ Service │      │ Service │      │ Service │
   └────┬────┘      └────┬────┘      └────┬────┘
        │                 │                 │
        └────────┬────────┴────────┬────────┘
                 │                   │
           ┌─────▼─────┐      ┌─────▼─────┐
           │  Database │      │  AI API   │
           │  MySQL    │      │(OpenAI等) │
           └───────────┘      └───────────┘
```

---

## 六、开发计划

### 阶段一：核心功能（MVP）

**目标**：完成可用的最小产品

| 模块 | 功能 | 优先级 |
|------|------|--------|
| 安装系统 | 环境检测、配置输入、自动安装 | P0 |
| 用户系统 | 注册、登录、登出、邮箱验证 | P0 |
| 页面管理 | 创建、编辑、删除页面 | P0 |
| 链接管理 | 添加、编辑、删除、排序链接 | P0 |
| 主题系统 | 基础主题（3-5套）、主题预览 | P0 |
| 公开页面 | 访问用户主页、点击链接 | P0 |
| 管理后台 | 用户管理、页面管理、站点设置 | P1 |
| 安装向导 | 企业风、简洁风格 | P0 |

**交付时间**：4-6周

---

### 阶段二：AI + 统计

**目标**：差异化功能

| 模块 | 功能 | 优先级 |
|------|------|--------|
| AI 生成 | 文字描述生成页面配置 | P0 |
| AI 配置 | 后台管理 AI API | P0 |
| AI 限制 | 每日调用次数限制 | P1 |
| 统计分析 | 基础点击统计 | P0 |
| 详细统计 | 访客追踪（设备、地域等） | P1 |
| 数据可视化 | 统计图表展示 | P1 |

**交付时间**：2-3周

---

### 阶段三：企业功能

**目标**：商业化准备

| 模块 | 功能 | 优先级 |
|------|------|--------|
| 企业升级 | 用户类型切换 | P0 |
| 域名绑定 | 自定义域名 | P0 |
| 品牌定制 | Logo、颜色、自定义CSS | P0 |
| 无限链接 | 企业用户不限链接数 | P0 |
| SEO 优化 | 自定义标题、描述 | P1 |
| 二维码 | 基础/高级二维码 | P1 |

**交付时间**：2-3周

---

### 阶段四：支付系统（可选）

**目标**：完成商业闭环

| 模块 | 功能 | 优先级 |
|------|------|--------|
| 支付集成 | 支付宝/微信/Stripe | P0 |
| 订阅管理 | 月付/年付/终身 | P0 |
| 订单管理 | 订单列表、退款 | P1 |
| 优惠券 | 优惠码系统 | P2 |

**交付时间**：3-4周

---

## 七、MVP 里程碑

```
Week 1-2:  安装系统 + 用户系统
Week 3-4:  页面管理 + 链接管理
Week 5-6:  主题系统 + 公开页面 + 管理后台
Week 7-8:  AI 生成 + 统计分析
Week 9-10: 企业功能 + 优化完善
Week 11+:  支付系统（可选）
```

---

## 八、技术规范

### 8.1 代码规范
- 遵循 PSR-12 编码规范
- 使用中文注释（根据用户偏好）
- MVC 架构模式
- 服务层（Services）处理业务逻辑

### 8.2 安全规范
- 所有用户输入必须验证和过滤
- 密码使用 bcrypt 加密
- 使用 prepared statements 防止 SQL 注入
- CSRF 令牌保护
- XSS 防护

### 8.3 性能优化
- 数据库查询使用索引
- 静态资源CDN加速
- 图片懒加载
- 数据库连接池
- 缓存策略

---

## 九、未来扩展

### 可选功能
- 小程序支持
- API 开放平台
- 模板市场
- 团队协作
- 多语言支持
- 深色模式切换

---

*文档版本：1.0*
*创建日期：2026-05-12*
*最后更新：2026-05-12*
