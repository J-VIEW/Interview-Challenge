# Task Management System

A modern, secure, and user-friendly PHP/JS application for managing users and tasks. Built with best practices for deployment on Azure App Service and local development.

---

## 🚀 Quick Start

1. **Clone or Download the Project**
   - Download the latest release as a `.zip` (see below for instructions) or clone the repo:
     ```bash
     git clone <repo-url>
     cd task-management-system
     ```
2. **Install Dependencies**
   - Requires PHP 8.1+, Composer, and MySQL.
   - Run:
     ```bash
     composer install
     ```
3. **Configure Environment**
   - Copy `.env.example` to `.env` and fill in your credentials (see below for Azure setup).
4. **Set Up the Database**
   - Import `database/schema.sql` into your MySQL server.
   - Run:
     ```bash
     php database/insert_admin_user.php
     ```
5. **Run Locally**
   - Start the PHP server:
     ```bash
     php -S 127.0.0.1:8000 -t public
     ```
   - Visit [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## ✨ Features
- Admin: Add/edit/delete users, assign tasks, view stats
- User: View/update tasks, dashboard
- Secure authentication (sessions, CSRF, rate limiting)
- Real-time updates (SSE)
- Email notifications (configurable)
- Modern, responsive UI
- Ready for Azure App Service deployment

---

## 📁 Project Structure
```
task-management-system/
├── api/                # PHP backend (OOP classes, endpoints)
├── database/           # SQL schema and admin setup scripts
├── logs/               # Application and error logs
├── public/             # Frontend (index.html, js, css)
├── vendor/             # Composer dependencies (not committed)
├── .env.example        # Sample environment config
├── README.md           # This file
└── ...
```

---

## 🛠️ Manual Setup Guide

### 1. **Environment Variables**
- Copy `.env.example` to `.env` and fill in:
  - `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_NAME`
  - `SESSION_SECRET`, `JWT_SECRET` (generate strong secrets)
  - SMTP/email settings if needed
  - Admin credentials (`ADMIN_USERNAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD_HASH`)
- **Do NOT commit your real `.env` file.**

### 2. **Database Setup**
- Import the schema:
  ```bash
  mysql -u <user> -p <db_name> < database/schema.sql
  ```
- Insert the admin user:
  ```bash
  php database/insert_admin_user.php
  ```

### 3. **Run Locally**
- Start the PHP server:
  ```bash
  php -S 127.0.0.1:8000 -t public
  ```
- Open [http://127.0.0.1:8000](http://127.0.0.1:8000)

### 4. **Azure App Service Deployment**
- Provision Azure resources (App Service, MySQL Flexible Server)
- Set environment variables in Azure Portal (App Service > Configuration):
  - `DB_HOST=taskmanagamentsystem-server.mysql.database.azure.com`
  - `DB_NAME=task_management`
  - `DB_USERNAME=myadmin@taskmanagamentsystem-server`
  - `DB_PASSWORD=<your-password>`
  - ...and all other required variables from `.env.example`
- Deploy code via GitHub Actions, Local Git, or FTP
- Set web root to `/public` (see Azure instructions above)
- Run `php database/insert_admin_user.php` via Azure SSH/Kudu if needed

---

## 👤 Default Admin Credentials (for testing)
- **Username:** `admin` (or as set in your `.env`)
- **Email:** `admin@example.com` (or as set in your `.env`)
- **Password:** (set your own, hash with `php -r "echo password_hash('yourpassword', PASSWORD_DEFAULT);"` and put in `ADMIN_PASSWORD_HASH`)

---

## 📦 Creating a Zip for Distribution
1. **Remove unnecessary files:**
   - Do NOT include `.env`, `logs/`, `vendor/` (unless you want to ship dependencies), or any local config files.
2. **Create the zip:**
   ```bash
   cd ..
   zip -r task-management-system.zip task-management-system -x "*.git*" "logs/*" "vendor/*" "*.env" "*.log"
   ```
3. **Send the zip to your recipient.**

---

## ❗ What NOT to Commit or Distribute
- `.env` (contains secrets)
- `logs/` (runtime logs)
- `vendor/` (can be rebuilt with Composer)
- Any local or sensitive config files

---

## 📝 FAQ & Tips
- **How do I reset the admin password?**
  - Generate a new hash and update `ADMIN_PASSWORD_HASH` in your environment.
- **How do I see logs?**
  - Check `logs/app.log` and `logs/error.log` (local only; Azure logs are ephemeral).
- **How do I add users or tasks?**
  - Log in as admin and use the dashboard UI.
- **How do I enable real email sending?**
  - Set SMTP variables in your environment and ensure your server allows outbound mail.
- **How do I run on Azure?**
  - See the Azure App Service section above for full instructions.

---

## 📬 Contact & Support
- For issues, open a GitHub issue or contact the maintainer.

---

## 🏁 You're ready to go!
- Follow the steps above to run, deploy, and manage your Task Management System.
- For any questions, check the FAQ or reach out for help.
# Interview-Challange
