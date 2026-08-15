<div align="right">

[🇨🇳 中文](README.md) | 🇺🇸 English

</div>

# 🎖️ TACTICAL BLOG

> Personal Blog & Private Drive  
> PHP / HTML / CSS / JavaScript / JSON

A lightweight personal blog system with an integrated private file drive.

The project is intentionally simple: no Node.js, npm, database, or large framework is required.

---

## ⚡ SYSTEM STATUS

**SYSTEM:** ONLINE  
**PLATFORM:** PHP 7.0+  
**DATABASE:** NOT REQUIRED  
**NODE.JS:** NOT REQUIRED  
**STORAGE:** JSON + FILE SYSTEM  
**UI:** TACTICAL / HUD

---

## 📡 Overview

TACTICAL BLOG is a personal website combining a blog and private file storage.

Main purposes:

- 📝 Publish personal articles
- 👤 Personal profile
- 🔗 Manage friend links
- 📥 Provide downloadable resources
- ☁️ Store personal files
- 🔐 Manage the site through an admin panel
- 📊 View basic site data

The interface uses a tactical terminal / HUD visual style.

# 🛰️ Features

## 📝 Article System

Supports creating, editing and deleting articles, tags, search, view counters, article detail pages and publication dates.

Article data is stored in JSON:

```text
data/posts.json
```

## ☁️ TACTICAL DRIVE

The built-in personal drive supports:

- File upload
- File download
- File deletion
- File listing
- File size display
- Modification time
- File search
- Folder paths
- Multiple file formats

Files are stored in:

```text
uploads/
```

Main supported formats:

```text
ZIP / RAR / 7Z
PDF / TXT
JPG / JPEG / PNG / GIF / WEBP
MP3 / MP4
DOC / DOCX / XLS / XLSX / PPT / PPTX
```

Default single-file upload limit: `100 MB`.

> The actual upload limit is also affected by the server's PHP configuration.

# 🔐 Admin Panel

The admin panel uses PHP sessions.

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

Administrators can publish, edit and delete articles, manage tags, upload/delete files, and change the site name, tagline and announcement.

# 🎨 UI Design

The interface follows a **Tactical / Military / HUD / Terminal** style.

Visual elements include:

- Black background
- Neon green accents
- HUD frames
- Terminal-style typography
- Scanlines
- Online status indicators
- Tactical dashboard

```text
[ SYSTEM ONLINE ]

TACTICAL BLOG

> PERSONAL LOG
> DATA STORAGE
> SYSTEM STATUS
```

# 🧩 Technology Stack

No Node.js is required.

```text
Frontend
├── HTML
├── CSS
└── JavaScript

Backend
└── PHP 7.0+

Data
├── JSON
└── File System
```

Not required:

```text
❌ Node.js
❌ npm
❌ Express
❌ MySQL
❌ MongoDB
❌ Composer
```

This makes the project suitable for ordinary PHP shared hosting.

# 📂 Project Structure

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

# 🚀 Deployment

You need a **PHP 7.0+** capable web host. Apache or Nginx is supported. Node.js is not required.

Upload the project to the web root, for example `/public_html/`, and make sure `/public_html/index.php` exists.

Copy `config.example.php` to `config.php` and configure the administrator settings. Do **not** commit `config.php` to a public GitHub repository.

Make sure PHP can write to `data/` and `uploads/`.

```bash
chmod 755 data
chmod 755 uploads
```

# 🔑 Admin Access

The admin area is located under `/admin/`. Log in with your administrator account to enter the `COMMAND CENTER`.

# ⚠️ Security Notes

For public deployment, it is recommended to use HTTPS, a strong administrator password, regular backups, and restricted upload file types.

Server-executable extensions such as these should never be accepted from untrusted users:

```text
.php
.phtml
.php5
.cgi
```

# 💾 Backup

Important site data is stored in:

```text
data/
uploads/
config.php
```

Back up these locations regularly.

# 🛠️ Roadmap

Current version:

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

Planned features:

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

This project is not designed to chase a complicated technology stack.

The goal is simple:

> **Write it yourself. Maintain it yourself. Keep it simple, stable and useful.**

No huge framework. No complex database. No Node.js.

A normal PHP shared host is enough to run the system.

# 🎖️ TACTICAL BLOG

```text
SYSTEM STATUS : ONLINE
MISSION       : PERSONAL WEBSITE
STORAGE       : ACTIVE
BLOG          : ACTIVE
NETWORK       : ACTIVE

[ END OF TRANSMISSION ]
```

## 📜 License

For personal learning, research and use. You may modify the project to fit your needs.
