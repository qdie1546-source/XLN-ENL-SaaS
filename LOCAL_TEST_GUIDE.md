# LinkHub 本地测试指南

## 🎯 快速开始

### 方式一：宝塔面板安装（推荐）

1. **上传文件**
   - 将整个项目上传到网站根目录
   
2. **创建数据库**
   - 登录宝塔面板
   - 进入数据库 → 添加数据库
   - 记住数据库名称、用户名和密码

3. **配置网站**
   - 添加站点 → 绑定域名
   - 设置网站目录为 `public`
   - 配置伪静态规则（使用 .htaccess）

4. **运行安装向导**
   - 访问 `http://你的域名/install.php`
   - 按照提示填写数据库信息
   - 创建管理员账号

5. **完成安装**
   - 删除 `install.php` 文件
   - 开始使用！

---

### 方式二：本地开发环境

#### 使用 PHP 内置服务器（仅预览 HTML）

```bash
# 进入项目目录
cd c:\Users\12657\Desktop\22

# 启动 PHP 服务器
php -S localhost:8080 -t public

# 访问 http://localhost:8080
```

#### 使用 XAMPP/WAMP/MAMP

1. **配置虚拟主机**
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/linkhub/public"
       ServerName linkhub.local
   </VirtualHost>
   ```

2. **导入数据库**
   - 访问 phpMyAdmin
   - 创建数据库 `linkhub_db`
   - 导入 `database/migrations/001_initial_schema.sql`

3. **配置环境变量**
   - 复制 `.env.example` 为 `.env`
   - 修改数据库连接信息

---

## 📁 重要文件说明

### 入口文件
- `public/index.php` - 主入口
- `public/install.php` - 安装向导

### 核心配置
- `config/app.php` - 应用配置
- `config/database.php` - 数据库配置
- `config/themes.php` - 主题配置

### 静态资源预览
- `test.html` - 测试页面
- `install.html` - 安装向导预览
- `templates/index.html` - 主题展示

---

## 🗄️ 数据库设置

### 表结构说明

#### users 用户表
```sql
- id: 用户ID
- email: 邮箱（唯一）
- password: 密码（已加密）
- name: 名称
- phone: 电话
- is_admin: 是否管理员
- is_active: 是否激活
- user_type: 用户类型（individual/enterprise）
- created_at: 创建时间
```

#### pages 页面表
```sql
- id: 页面ID
- user_id: 用户ID
- slug: 页面地址（唯一）
- title: 页面标题
- bio: 个人简介
- theme_id: 主题ID
- is_published: 是否发布
- view_count: 浏览数
- click_count: 点击数
```

#### links 链接表
```sql
- id: 链接ID
- page_id: 页面ID
- title: 链接标题
- url: 链接地址
- icon: 图标
- position: 排序
- is_active: 是否启用
- click_count: 点击数
```

---

## 🎨 主题系统

### 已内置主题
1. **minimal** - 极简风格
2. **gradient** - 渐变风格
3. **card** - 卡片风格
4. **dark** - 暗色风格
5. **vintage** - 复古风格

### 预览主题
访问 `templates/index.html` 查看所有主题效果

---

## 🔧 常见问题

### Q: 页面空白或500错误
- 检查 PHP 版本是否 >= 7.4
- 检查数据库连接是否正确
- 查看 `storage/logs` 日志

### Q: 静态资源无法加载
- 确保 `.htaccess` 文件存在
- 确认 URL 重写已启用

### Q: 安装向导无法运行
- 检查是否有写入权限
- 确保 PDO 扩展已启用

---

## 📊 测试步骤

### 1. 预览静态页面
- `test.html` - 项目介绍
- `install.html` - 安装向导预览
- `templates/index.html` - 主题展示

### 2. 运行完整安装
1. 访问 `/install.php`
2. 填写数据库信息
3. 创建管理员账号
4. 删除 install.php

### 3. 测试核心功能
- 注册/登录账号
- 创建链接页面
- 添加社交链接
- 更换主题
- 查看公开页面
- 访问管理后台

---

## 🚀 下一步

项目已就绪！选择一个最适合你的方式进行安装：

1. **宝塔面板** - 最简单，适合生产环境
2. **本地 XAMPP** - 适合开发测试
3. **在线演示** - 部署到免费托管服务

有任何问题请随时询问！
