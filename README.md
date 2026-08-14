<div align="right">

🇨🇳 中文 | [🇺🇸 English](README.en.md)

</div>

# 🎖️ TACTICAL BLOG

> Personal Blog & Private Drive  
> PHP / HTML / CSS / JavaScript / JSON

一个自己编写的个人博客系统，同时集成个人网盘功能。

项目整体采用轻量化设计，不使用 Node.js、npm 或数据库。

---

## ⚡ SYSTEM STATUS

**SYSTEM:** ONLINE  
**PLATFORM:** PHP  
**DATABASE:** NOT REQUIRED  
**NODE.JS:** NOT REQUIRED  
**STORAGE:** JSON + FILE SYSTEM  
**UI:** TACTICAL / HUD

---

## 📡 项目简介

这是一个个人博客 + 个人网盘系统。

主要用于：

- 📝 发布个人文章
- 👤 个人介绍
- 🔗 管理友情链接
- 📥 提供文件下载
- ☁️ 保存个人文件
- 🔐 后台管理网站
- 📊 查看网站数据

网站采用战术终端 / HUD 风格设计。

# 🛰️ 主要功能

## 📝 文章系统

支持创建、编辑、删除文章，文章标签、搜索、阅读量统计、详情页和发布时间记录。文章数据使用 JSON 保存于 `data/posts.json`。

## ☁️ TACTICAL DRIVE

内置个人网盘，支持文件上传、下载、删除、列表、大小与修改时间显示、搜索、文件夹路径和多种文件格式。文件保存在 `uploads/`。

主要支持：

```text
ZIP / RAR / 7Z
PDF / TXT
JPG / JPEG / PNG / GIF / WEBP
MP3 / MP4
DOC / DOCX / XLS / XLSX / PPT / PPTX
```

默认单文件上传限制为 `100 MB`，实际限制还会受到服务器 PHP 配置影响。

# 🔐 后台管理

后台采用 PHP Session 登录：

```text
COMMAND CENTER
│
├── Dashboard
├── Article Management
├── New Article
├── File Manager
├── Site Settings
└── Logout
```

可以在后台发布、编辑、删除文章，添加标签，上传/删除文件，以及修改网站名称、简介和公告。

# 🎨 UI Design

网站采用 **Tactical / Military / HUD / Terminal** 风格，包含黑色背景、荧光绿色、HUD 线框、Terminal 字体、Scanline、Online Status 和 Tactical Dashboard。

```text
[ SYSTEM ONLINE ]

TACTICAL BLOG

> PERSONAL LOG
> DATA STORAGE
> SYSTEM STATUS
```

# 🧩 技术架构

本项目不使用 Node.js：

```text
Frontend
├── HTML
├── CSS
└── JavaScript

Backend
└── PHP

Data
├── JSON
└── File System
```

不需要：

```text
❌ Node.js
❌ npm
❌ Express
❌ MySQL
❌ MongoDB
❌ Composer
```

适合部署在普通 PHP 虚拟主机。

# 📂 项目结构

```text
blog/
├── index.php
├── post.php
├── about.php
├── download.php
├── links.php
├── drive.php
├── admin/
│   ├── index.php
│   ├── dashboard.php
│   ├── login.php
│   ├── logout.php
│   ├── posts.php
│   ├── post_edit.php
│   ├── files.php
│   └── settings.php
├── inc/
│   ├── bootstrap.php
│   ├── header.php
│   └── footer.php
├── assets/
│   └── style.css
├── data/
│   ├── posts.json
│   ├── links.json
│   └── downloads.json
├── uploads/
├── config.example.php
└── .gitignore
```

# 🚀 部署

需要支持 PHP 的虚拟主机，建议 PHP 8.0+、Apache 或 Nginx，不需要 Node.js。

将项目上传到网站根目录，例如 `/public_html/`，确保 `/public_html/index.php` 存在。

复制 `config.example.php` 为 `config.php` 并填写管理员配置。`config.php` 不应提交到公开 GitHub 仓库。

确保 PHP 可以写入 `data/` 和 `uploads/`。Linux 环境可根据服务器要求使用：

```bash
chmod 755 data
chmod 755 uploads
```

# 🔑 后台

后台位于 `/admin/`，进入登录页面后使用管理员账号登录，成功后进入 `COMMAND CENTER`。

# ⚠️ 安全注意事项

部署到公网后建议使用 HTTPS、强管理员密码、定期备份 `data/` 与 `uploads/`，并限制可上传文件类型。

不要允许普通用户上传服务器可执行文件，例如：

```text
.php
.phtml
.php5
.cgi
```

# 💾 数据备份

重要数据主要位于：

```text
data/
uploads/
config.php
```

建议定期完整备份以上内容。

# 🛠️ Roadmap

当前版本：

```text
[v2.x]
[x] Personal Blog
[x] Article System
[x] Article Search
[x] Article Tags
[x] View Counter
[x] Link System
[x] Download System
[x] Admin Login
[x] Admin Dashboard
[x] File Upload
[x] File Download
[x] File Delete
[x] Tactical UI
[x] Site Settings
[x] Personal Drive
```

计划功能：

```text
[ ] Multiple File Upload
[ ] Drag & Drop Upload
[ ] Upload Progress
[ ] Folder Management
[ ] Create Folder
[ ] Rename File
[ ] Move File
[ ] File Search
[ ] Image Preview
[ ] File Sharing Links
[ ] Share Expiration
[ ] Storage Statistics
[ ] User Management
```

# 📡 Project Philosophy

这个项目不是为了追求复杂技术栈，目标很简单：

> **自己写、自己维护、简单、稳定、够用。**

不依赖庞大框架，不需要复杂数据库，也不需要 Node.js。一台普通 PHP 虚拟主机就可以运行整个系统。

# 🎖️ TACTICAL BLOG

```text
SYSTEM STATUS : ONLINE
MISSION       : PERSONAL WEBSITE
STORAGE       : ACTIVE
BLOG          : ACTIVE
NETWORK       : ACTIVE

[ END OF TRANSMISSION ]
```

---

## 📜 License

本项目仅供个人学习、研究和使用。你可以根据自己的需求修改代码。
