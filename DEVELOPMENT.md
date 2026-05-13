# LinkHub 开发文档

## 项目概述

LinkHub 是一个社交链接聚合平台，采用原生 PHP 8.4 开发（无框架），Tailwind CSS 构建前端 UI，SQLite 作为本地开发数据库。

- **项目路径**: `c:\Users\12657\Desktop\22`
- **开发服务器**: `http://localhost:8000`
- **PHP 版本**: 8.4.21 (CLI, ZTS, Visual C++ 2022 x64)
- **数据库**: SQLite（`database/linkhub_dev.sqlite`）

---

## 目录结构

```
project-root/
├── app/
│   ├── Controllers/          # 控制器
│   │   ├── Admin/            #   管理后台控制器
│   │   ├── Api/              #   API 控制器
│   │   └── *.php             #   用户端控制器
│   ├── Helpers/              # 全局辅助函数
│   ├── Libraries/            # 核心类库 (Session, Database, Config, Response)
│   ├── Models/               # 数据模型
│   └── Views/                # 视图模板
│       ├── admin/            #   管理后台页面
│       ├── auth/             #   登录/注册页面
│       ├── dashboard/        #   用户仪表板
│       ├── home/             #   首页
│       ├── layouts/          #   共享布局 (header, footer, sidebar)
│       ├── pages/            #   页面管理
│       ├── public/           #   公开页面
│       └── themes/           #   主题浏览
├── config/                   # 配置文件
├── database/                 # 数据库相关 (schema, seed, migrations)
├── public/                   # Web 入口
├── routes/                   # 路由定义
├── vendor/                   # 自定义 PSR-4 自动加载器
├── .env                      # 环境变量
└── storage/sessions/         # Session 文件
```

---

## 架构设计

### 自动加载

`vendor/autoload.php` — 自定义 PSR-4 自动加载器，同时支持 `App\` 和 `LinkHub\`（兼容旧代码）命名空间，都映射到 `app/` 目录。同时加载了三个 helper 文件。

**命名空间规范**（当前统一为 `App\`）：
- 控制器: `App\Controllers\*` / `App\Controllers\Admin\*`
- 模型: `LinkHub\Models\*`（历史遗留，autoloader 兼容）
- 核心库: `App\Libraries\*`
- API: `App\Controllers\Api\*`

### 请求生命周期

```
public/router.php          # PHP 内置服务器路由
  → 静态文件 / 模板文件    # 直接返回
  → public/index.php       # 动态请求入口
      → 加载 .env
      → 加载 autoload.php
      → Config::load()     # 加载 config/ 目录下所有 .php 配置
      → 解析 REQUEST_URI
      → 匹配 routes/web.php 路由表
      → 解析 Controller@action 或 Controller/action
      → 实例化控制器 → 调用方法
```

### 路由格式

`routes/web.php` 返回数组，支持两种格式：
- `Controller@action` — 如 `DashboardController@index`
- `Controller/action` — 如 `Auth/login`

`{param}` 占位符匹配 `[a-zA-Z0-9_-]+`。

### 控制器基类

`App\Controllers\Controller` 提供：
- `view($template, $data)` — 渲染视图
- `redirect($url)` — 重定向
- `back()` — 返回上一页
- `json($data, $status)` — JSON 响应
- `auth()` — 获取当前用户（Session::get('user')）
- `requireAuth()` — 鉴权中间件（未登录跳转 /login）

### 数据访问

- `App\Libraries\Database` — PDO 封装（支持 MySQL / SQLite）
  - `getInstance()` — 单例
  - `fetch($sql, $params)` — 查询单行
  - `fetchAll($sql, $params)` — 查询所有行
  - `query($sql, $params)` — 执行语句
  - `lastInsertId()` / `lastId()` — 最后插入 ID

- `LinkHub\Models\Model` — 基础 Model 类
  - `find($id)`, `findBy($field, $value)`, `all()`
  - `create($data)`, `update($id, $data)`, `delete($id)`
  - `count()`, `recent($limit)`
  - 自动拼接表前缀 `lh_`

### Session

`App\Libraries\Session` — 静态类，自动调用 `session_start()`：
- `set($key, $value)`, `get($key, $default)`, `has($key)`
- `remove($key)`, `flash($key, $value)`
- `destroy()`

Session 文件保存路径: `storage/sessions/`

### 全局 Helper 函数

位于 `app/Helpers/`：
| 函数 | 文件 | 用途 |
|------|------|------|
| `url($path)` | url.php | 生成完整 URL |
| `asset($path)` | url.php | 生成静态资源 URL |
| `e($value)` | utils.php | HTML 转义 |
| `dd($data)` | utils.php | var_dump + exit |
| `old($key, $default)` | utils.php | 获取旧表单值 |
| `redirect($url)` | utils.php | 重定向 |
| `back()` | utils.php | 返回上一页 |
| `view($template, $data)` | utils.php | 渲染视图 |
| `session($key, $value)` | session.php | Session 读写 |
| `auth()` | session.php | 获取当前用户 |
| `auth_check()` | session.php | 检查登录状态 |

---

## 数据库表结构

所有表使用 `lh_` 前缀。

| 表名 | 说明 | 关键字段 |
|------|------|----------|
| `lh_users` | 用户 | id, email, password(哈希), name, is_admin, is_active, user_type |
| `lh_enterprise_profiles` | 企业信息 | user_id, company_name, custom_domain |
| `lh_pages` | 用户页面 | id, user_id, slug(唯一), title, bio, theme_id, view_count, click_count |
| `lh_links` | 链接 | id, page_id, title, url, position, link_type, click_count |
| `lh_themes` | 主题 | id, name, slug(唯一), css_content, is_free, is_active |
| `lh_statistics` | 访问统计 | id, link_id, page_id, ip_address, device_type, browser |
| `lh_ai_settings` | AI 配置 | provider, api_key, model, daily_limit |
| `lh_ai_usage_logs` | AI 用量 | user_id, prompt_tokens, completion_tokens |
| `lh_install_config` | 安装配置 | install_key, site_url, is_installed |

---

## 路由表

### 用户端

| 方法 | 路径 | 处理器 | 说明 |
|------|------|--------|------|
| GET | `/` | HomeController@index | 首页 |
| GET | `/login` | AuthController@login | 登录页 |
| POST | `/login` | AuthController@doLogin | 登录处理 |
| GET | `/register` | AuthController@register | 注册页 |
| POST | `/register` | AuthController@doRegister | 注册处理 |
| GET/POST | `/logout` | AuthController@logout | 登出 |
| GET | `/dashboard` | DashboardController@index | 用户仪表板 |
| GET | `/pages` | PageController@index | 我的页面列表 |
| GET | `/pages/create` | PageController@create | 创建页面 |
| POST | `/pages` | PageController@store | 保存页面 |
| GET | `/pages/{id}/edit` | PageController@edit | 编辑页面 |
| POST | `/pages/{id}` | PageController@update | 更新页面 |
| POST | `/pages/{id}/delete` | PageController@delete | 删除页面 |
| GET | `/pages/{pageId}/links` | LinkController@index | 链接列表 |
| POST | `/links` | LinkController@store | 添加链接 |
| POST | `/links/{id}` | LinkController@update | 更新链接 |
| POST | `/links/{id}/delete` | LinkController@delete | 删除链接 |
| POST | `/links/reorder` | LinkController@reorder | 排序链接 |
| GET | `/themes` | ThemeController@index | 主题浏览 |
| POST | `/themes/{id}/apply` | ThemeController@apply | 应用主题 |
| GET | `/analytics` | AnalyticsController@index | 数据分析 |
| GET/POST | `/ai/generate` | AIController@generate | AI 生成 |
| GET | `/ai/history` | AIController@history | AI 历史 |
| GET/POST | `/enterprise` | EnterpriseController@* | 企业功能 |
| GET | `/track/link/{id}` | TrackController@link | 链接追踪 |
| GET | `/{slug}` | PublicController@page | 公开页面 |
| GET | `/{slug}/qr` | PublicController@qr | 页面二维码 |

### 管理后台

| 方法 | 路径 | 处理器 | 说明 |
|------|------|--------|------|
| GET | `/admin/login` | Admin\AuthController@login | 管理员登录页 |
| POST | `/admin/login` | Admin\AuthController@doLogin | 管理员登录 |
| GET/POST | `/admin/logout` | Admin\AuthController@logout | 管理员登出 |
| GET | `/admin` | Admin\DashboardController@index | 管理仪表盘 |
| GET | `/admin/users` | Admin\UserController@index | 用户管理 |
| GET | `/admin/users/{id}/edit` | Admin\UserController@edit | 编辑用户 |
| POST | `/admin/users/{id}` | Admin\UserController@update | 更新用户 |
| GET | `/admin/pages` | Admin\PageController@index | 页面管理 |
| GET | `/admin/config` | Admin\ConfigController@index | 系统配置 |
| POST | `/admin/config/update` | Admin\ConfigController@update | 更新配置 |
| GET | `/admin/themes` | Admin\ThemeController@index | 主题管理 |
| GET | `/admin/settings` | Admin\SettingController@index | 系统设置 |
| GET | `/admin/stats` | Admin\StatsController@index | 统计面板 |

---

## 主题系统

内置 5 个主题（`config/themes.php`）：

| ID | Slug | 名称 | 风格 |
|----|------|------|------|
| 1 | minimal | Minimal | 极简白底，蓝色主题色 |
| 2 | gradient | Gradient | 渐变紫背景 |
| 3 | card | Card | 卡片风格 |
| 4 | dark | Dark | 暗色模式 |
| 5 | vintage | Vintage | 复古暖色 |

主题数据同时存储在 `config/themes.php` 和 `lh_themes` 表。

---

## 环境配置 (.env)

```ini
APP_NAME=LinkHub
APP_URL=http://localhost:8000
APP_ENV=development
APP_DEBUG=true

DB_DRIVER=sqlite
DB_HOST=localhost
DB_PORT=3306
DB_NAME=linkhub_dev
DB_USER=root
DB_PASS=
DB_PREFIX=lh_

AI_PROVIDER=openai
AI_API_KEY=
AI_API_ENDPOINT=
AI_MODEL=gpt-3.5-turbo
AI_DAILY_LIMIT=100

SESSION_LIFETIME=7200
CACHE_DRIVER=file
```

---

## 启动开发服务器

### 前置条件
- PHP 8.4+ (含 pdo_sqlite, pdo_mysql, mbstring 扩展)
- PHP 路径: `C:\Users\12657\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.4_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe`

### 启动命令

```bash
cd c:\Users\12657\Desktop\22
php -S localhost:8000 -t public/ public/router.php
```

### 初始化数据库

```bash
php database/setup_dev.php
```

---

## 测试账号

| 角色 | 邮箱 | 密码 | 页面 |
|------|------|------|------|
| 管理员 | admin@linkhub.com | admin123456 | /admin |
| 普通用户 | test@linkhub.com | test123456 | /testuser, /testuser-dark |

---

## PHP 关键配置 (php.ini)

```
extension_dir = ext
extension=pdo_sqlite
extension=pdo_mysql
extension=mbstring
session.save_path = "c:/Users/12657/Desktop/22/storage/sessions"
session.use_cookies = 1
session.auto_start = 0
```

---

## 常见问题

### Class not found
检查命名空间是否为 `App\*`，autoloader 同时支持 `App\` 和 `LinkHub\`。

### Session/Cookie 未生效
确保使用 `Session::set()` 而非直接操作 `$_SESSION`，后者需要手动 `session_start()`。

### 表名未找到
原始 SQL 需带 `lh_` 前缀，推荐使用 Model 类自动拼接前缀。

### 仪表板等页面返回 0 字节
查看日志：PHP server 终端输出或 `php_errors.log`，常见为类加载失败。

### SQLite 路径
Database 构造器自动将相对路径映射至 `database/<name>.sqlite`。
