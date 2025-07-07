<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Vilt File Management System

A modern, responsive file management system built with Laravel and Tailwind CSS.

## Features

### 📁 File Management
- Upload files with drag & drop support
- File organization with folders and subfolders
- File type detection with appropriate icons
- File size formatting (B, KB, MB, GB, TB)
- File download functionality
- File deletion with confirmation

### 📂 Folder Management
- Create folders and subfolders
- Hierarchical folder structure
- Folder deletion (with recursive file deletion)
- Folder navigation with breadcrumbs

### 📊 Dashboard
- File and folder statistics
- Recent files and folders
- Quick actions for upload and folder creation
- Storage usage overview

### 🎨 User Interface
- Modern, responsive design
- Mobile-first approach
- Grid and list view for files
- Dark mode support
- Font Awesome icons
- Tailwind CSS styling

### 🔐 Security
- User authentication required
- File ownership validation
- Public/private file support
- CSRF protection
- File upload validation

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Copy environment file:
   ```bash
   cp .env.example .env
   ```

4. Configure your database in `.env`

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Run migrations:
   ```bash
   php artisan migrate
   ```

7. Create storage link:
   ```bash
   php artisan storage:link
   ```

8. Build assets:
   ```bash
   npm run build
   ```

9. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

1. Register a new account or login
2. Navigate to the dashboard to see your file statistics
3. Use the "Files" section to browse and manage your files
4. Create folders to organize your files
5. Upload files using the upload button or drag & drop
6. Download or delete files as needed

## File Structure

```
app/
├── Http/Controllers/
│   ├── FileManagementController.php
│   └── FolderController.php
├── Models/
│   ├── File.php
│   ├── Folder.php
│   └── User.php
resources/views/
├── file-management/
│   ├── dashboard.blade.php
│   └── index.blade.php
└── layouts/
    ├── app.blade.php
    └── navigation.blade.php
database/migrations/
├── create_folders_table.php
└── create_files_table.php
```

## Technologies Used

- **Laravel 11** - PHP framework
- **Tailwind CSS** - Utility-first CSS framework
- **Font Awesome** - Icon library
- **Alpine.js** - JavaScript framework (included with Laravel)
- **MySQL/PostgreSQL** - Database

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
