# Lovehills Wholesale Assistant

A modern, production-ready wholesale inventory and sales management system built with Laravel 10, featuring real-time stock tracking, concurrent sales processing, and comprehensive reporting.

## Features

### 🎯 Core Functionality
- **Product Management** - Add, edit, and delete products
- **Stock Management** - FIFO inventory tracking with price history
- **Sales Processing** - Multi-product sales with thermal receipt printing
- **Sales Reports** - Date-range filtering with user attribution
- **User Management** - Admin dashboard for managing staff accounts
- **Dark Mode** - Seamless light/dark theme switching

### 🔒 Security & Concurrency
- **Database Locking** - Pessimistic locking prevents race conditions during concurrent sales
- **User Status Check** - Deactivated accounts cannot login or access system
- **Role-Based Access** - Admin vs Sales user permissions
- **CSRF Protection** - Laravel's built-in security features

### 🎨 Modern UI/UX
- **Responsive Design** - Mobile-first approach with Tailwind CSS
- **Clean Interface** - Minimal, professional design with solid blue (#2563eb) accents
- **Real-time Updates** - Dynamic charts and notifications
- **Thermal Printing** - 80mm POS receipt format

## Tech Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Blade Templates, Tailwind CSS, Vanilla JavaScript
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Fortify + Jetstream
- **Charts**: Chart.js

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js >= 16.x
- NPM or Yarn

## Installation

### 1. Clone Repository
```bash
git clone 
cd lovehills
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lovehills
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Build Assets
```bash
npm run build
# or for development
npm run dev
```

### 7. Start Server
```bash
php artisan serve
```



**Login**: Use your password only (no email/username required)

## Database Schema

### Users
- `id`, `name`, `email`, `password`
- `usertype` (1=Admin, 0=Sales)
- `status` (1=Active, 0=Deactivated)
- `created_by` (User who created this account)

### Products
- `id`, `name`, `quantity`

### Stocks
- `id`, `product_id`, `quantity`, `price`, `date`
- FIFO tracking for inventory deduction

### Sales
- `id`, `product_id`, `quantity`, `price`, `total`
- `customer_name`, `payment_type`, `sale_date`
- `user_id` (Cashier who made the sale)

## Key Features Explained

### Concurrent Sales Processing
The system uses database row-level locking (`lockForUpdate()`) to prevent overselling when multiple users process sales simultaneously:



### FIFO Stock Management
Stock is deducted in First-In-First-Out order:
1. Oldest stock entries are used first
2. Prevents stock discrepancies
3. Maintains accurate inventory valuation

### User Status Enforcement
- Deactivated users cannot login
- Active sessions are terminated if account is deactivated
- Middleware checks status on every request

## User Roles

### Admin (usertype = 1)
- Full system access
- Product & stock management
- User management
- All reports and analytics

### Sales (usertype = 0)
- Sales processing
- View reports
- Limited dashboard access

## API Endpoints

### Products
- `GET /products-list` - List all products
- `POST /add-product` - Create product
- `DELETE /delete-product/{id}` - Delete product

### Stock
- `GET /stock-list` - List stock entries
- `POST /add-stock` - Add stock
- `PATCH /update-stock/{id}` - Increment stock
- `PATCH /update-price/{id}` - Update price
- `DELETE /delete-stock/{id}` - Delete stock

### Sales
- `POST /add-sale` - Process sale (with locking)
- `GET /track-sales-data` - Get sales report

### Admin
- `GET /admin/users/list` - List users
- `POST /admin/users/create` - Create user
- `PUT /admin/users/{id}` - Update user
- `PATCH /admin/users/{id}/toggle-status` - Activate/deactivate
- `DELETE /admin/users/{id}` - Delete user

## Printing

### Thermal Receipt (80mm)
Sales receipts and reports are formatted for 80mm thermal POS printers:
- Monospace font for alignment
- Dashed line separators
- Business header with logo placeholder
- Item-by-item breakdown
- Total and timestamp


**Version**: 1.0.0  
**Last Updated**: February 2026  
**Built with**: Laravel 10 + Tailwind CSS
