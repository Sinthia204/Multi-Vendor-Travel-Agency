"[php]": { "editor.formatOnSave": false }# TravelNest Documentation

## 1. Project Overview

TravelNest is a Laravel-based travel booking platform that provides a modern user experience for planning trips and a full admin panel for managing content and operations.

### Key Features

- Public landing page and booking flow
- User authentication and session-based login
- Admin panel with dashboards and management screens
- Hotel and transport management (CRUD)
- Booking and payment management
- Payment gateway integration for Bangladesh (SSLCommerz)

### Target Users

- Travelers booking tours and packages
- Admins managing bookings, users, and payments
- Agencies or staff maintaining travel content

## 2. Tech Stack

- Backend: Laravel
- Frontend: Blade templates
- Database: MySQL
- Payments: SSLCommerz (bKash, Nagad, Card)

## 3. Installation Guide

### 3.1 Clone the Repository

```bash
git clone <your-repo-url>
cd travel-app
```

### 3.2 Install Dependencies

```bash
composer install
npm install
```

### 3.3 Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Set your database and payment configuration in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travelnest
DB_USERNAME=root
DB_PASSWORD=

SSLCOMMERZ_STORE_ID=your_store_id
SSLCOMMERZ_STORE_PASSWORD=your_store_password
SSLCOMMERZ_SANDBOX=true
```

### 3.4 Run Migrations

```bash
php artisan migrate
```

### 3.5 Run the App

```bash
php artisan serve
```

## 4. Project Structure

- `app/`
    - Controllers, Models, Middleware, Providers
- `routes/`
    - Web and API routes
- `resources/views/`
    - Blade templates for UI
- `database/`
    - Migrations, seeders, factories

## 5. Module Explanation

### Users

- Registration and login flows
- User records stored in `users` table
- Admins can manage users from the admin panel

### Bookings

- Users create bookings for packages
- Stored in `bookings` table
- Status can be pending or confirmed

### Packages

- Admins manage package listings and details
- Public view shows packages and booking options

### Payments

- Payment records stored in `payments`
- Integrated with SSLCommerz for bKash, Nagad, and Card
- Admin can view payment status and logs

### Reports

- Admin reports show KPIs and charts
- Covers revenue, bookings, users, coupons, and inventory

### Coupons

- Admins create and manage coupon codes
- Stored in `coupons` table
- Coupons can be applied on the checkout page
- Discounts are saved in the booking record

### Settings

- Settings is reserved for system-wide configuration
- Typical items include payment keys, mail settings, and branding
- In this project, the page is a placeholder for future admin controls

## 6. Authentication System

- Session-based authentication
- Admin and user roles are separate
- Admin routes are protected by `auth` and `admin` middleware
- Login and logout handled by auth controllers

## 7. Payment System

### How Payment Works

1. User creates a booking
2. User initiates payment from checkout
3. Payment request sent to SSLCommerz
4. Gateway returns success or failure
5. Payment and booking are updated

### Supported Methods

- bKash
- Nagad
- Card (Visa or Mastercard)

### Payment Flow (Text Diagram)

```
User -> Checkout -> Payment Initiate -> SSLCommerz
SSLCommerz -> Success/Fail Callback -> Verify -> Update Payment + Booking
```

## 8. How to Edit & Update the Project

### Edit Blade UI (Views)

- Files live in `resources/views/`
- Example: `resources/views/payments/checkout.blade.php`

### Edit Controller Logic

- Controllers are in `app/Http/Controllers/`
- Example: `PaymentController.php`

### Edit Routes

- Web routes live in `routes/web.php`

### Add a New Feature

1. Create a controller in `app/Http/Controllers`
2. Add a route in `routes/web.php`
3. Create a Blade view in `resources/views`

### Update an Existing Module (Example: Bookings)

- Update booking logic in `BookingController`
- Update booking views in `resources/views/admin/bookings.blade.php`
- Update related migrations or models if needed

### Update Database

1. Create a new migration
2. Run `php artisan migrate`

### Maintain Admin Panel

- Keep consistent UI patterns and layouts in `resources/views/layouts/admin.blade.php`
- Use shared partials for sidebar and header

## 9. API / Route Documentation

### Public Routes

- `/` : Landing page
- `/login` : Opens login modal
- `/register` : Registration page
- `/logout` : Log out user

### Booking Routes (Auth Required)

- `/bookings/from-package` : Create booking from a package
- `/payment/checkout/{booking}` : Show checkout
- `/payment/initiate` : Start payment

### Payment Callbacks

- `/payment/success` : Gateway success callback
- `/payment/fail` : Gateway fail callback
- `/payment/cancel` : Gateway cancel callback
- `/payment/ipn` : Gateway IPN callback

### Admin Routes (Auth + Admin)

- `/admin` : Dashboard
- `/admin/users` : User management
- `/admin/bookings` : Booking management
- `/admin/packages` : Package management
- `/admin/payments` : Payment management
- `/admin/reports` : Reports
- `/admin/hotels` : Hotel management
- `/admin/transport` : Transport management
- `/admin/coupons` : Coupon management
- `/admin/settings` : Admin settings placeholder

## 10. Best Practices

- Use CSRF protection for all forms
- Validate all user input in controllers
- Keep business logic inside controllers and services
- Follow MVC pattern strictly
- Reuse Blade partials for shared UI

## 11. Future Improvements

- Email notifications for bookings and payments
- Role-based permissions with policies
- Export reports to CSV or PDF
- Real-time dashboard analytics
- Public API for partners

## 12. Common Errors & Fixes

### Payment Fails with Unauthorized

- Check SSLCommerz credentials in `.env`
- Clear config cache: `php artisan config:clear`

### Storage Image Not Loading

- Run `php artisan storage:link`
- Ensure file permissions allow access

### App Key Missing

- Run `php artisan key:generate`

### Cache Issues

- Clear cache: `php artisan cache:clear`
