<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
</p>

---

## 📚 Library Management System – Laravel 8

A simple Library Management System built with Laravel 8. This project supports **CRUD operations** for books and includes features like many-to-many relationships with authors and categories, filtering, validation, and soft deletes.

---

## 🔧 Features

-   Book Management (Create, Read, Update, Delete)
-   Many-to-Many Relationship:
    -   Books ↔ Authors
    -   Books ↔ Categories
-   Book status (`Available` or `Booked`)
-   Location tracking (e.g., shelf number)
-   Unique book name validation
-   Search & filter by:
    -   Author name
    -   Category name
    -   Book location
-   Laravel Seeders for Authors and Categories
-   PSR-12 compliant codebase

---

## 🚀 Technologies Used

-   Laravel 8
-   PHP 7.4+
-   MySQL 8
-   Composer 1
-   Postman (for API testing)

---

## 📥 Installation

Follow these steps to get the project up and running:

```bash
# 1. Clone the repository
git clone https://github.com/your-username/library-management-system.git

cd library-management-system

# 2. Install dependencies
composer install

# 3. Copy .env file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Set up database connection in your .env file

# 6. Run database migrations and seeders
php artisan migrate --seed

# 7. Serve the application
php artisan serve
```
