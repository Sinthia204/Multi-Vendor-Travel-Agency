# TravelNest Backend Analysis

## Project Summary

**TravelNest** is a Laravel 10 travel booking platform. The system has three major user areas:

- **Public visitor area**: browse destinations, hotels, packages, experiences, stories, and contact page.
- **Customer area**: register/login, book travel packages, apply coupons, pay online/demo, download invoice.
- **Admin and agency area**: admin manages platform data; approved agencies manage their own packages.

This file explains the backend so you can confidently answer interview questions about project architecture, routes, models, relationships, authentication, booking, payment, and admin workflow.

---

## Technology Stack

| Layer | Used In Project |
|---|---|
| Backend framework | Laravel 10 |
| Language | PHP 8.1+ |
| Database ORM | Eloquent |
| Auth | Laravel session auth, Sanctum API auth, agency guard, Socialite |
| Roles/permissions | Spatie Laravel Permission plus `role` / `is_admin` fields |
| Payment | ShurjoPay service integration plus local demo payment |
| PDF | barryvdh/laravel-dompdf |
| Queue job | `SendPaymentConfirmationJob` |
| Log tools | Laravel logging and Opcodes Log Viewer |
| Frontend views | Blade templates |

---

## Main Folder Structure

```text
travel-app/
├── app/
│   ├── Console/Commands/          # Scheduled/backend commands
│   ├── Http/Controllers/          # Web, admin, agency, payment controllers
│   ├── Http/Middleware/           # Auth/admin/agency approval middleware
│   ├── Http/Requests/             # Form validation request classes
│   ├── Http/Resources/            # API response resources
│   ├── Jobs/                      # Queue jobs
│   ├── Models/                    # Eloquent models
│   └── Services/Payments/         # ShurjoPay payment service
├── config/                        # Laravel and package configuration
├── database/
│   ├── migrations/                # Database table definitions
│   ├── seeders/                   # Demo/default data
│   └── factories/                 # Test data factories
├── resources/views/               # Blade UI pages
├── routes/
│   ├── web.php                    # Main browser routes
│   └── api.php                    # Sanctum API auth routes
├── tests/                         # PHPUnit tests
└── learning/                      # Interview preparation notes
```

---

## Route Architecture

### Public Web Routes

Defined in `routes/web.php`.

```php
GET /              -> PageController@home
GET /destinations  -> PageController@destinations
GET /packages      -> PageController@packages
GET /experiences   -> PageController@experiences
GET /stories       -> PageController@stories
GET /contact       -> PageController@contact
```

**Interview explanation:**

The public routes are read-only pages. They load active/featured records from models such as `Hotel`, `Package`, `Experience`, `Story`, `HomeContent`, and `PageHero`, then pass them to Blade views.

---

## Authentication System

### Customer Authentication

Main files:

- `AuthController`
- `RegistrationController`
- `Auth/SocialiteController`
- `routes/web.php`
- `app/Models/User.php`

Customer routes:

```php
GET  /login
POST /login
GET  /register
POST /register
POST /logout
GET  /auth/{provider}/redirect
GET  /auth/{provider}/callback
```

**How it works:**

1. User visits login or register page.
2. Laravel validates the form request.
3. Password login uses Laravel session authentication.
4. Social login uses Laravel Socialite.
5. Authenticated users can create bookings and make payments.

### Admin Authentication

Admin routes:

```php
GET  /admin/login
POST /admin/login
GET  /admin/dashboard
```

Admin panel uses:

```php
Route::prefix('admin')->middleware(['auth', 'admin'])
```

**Meaning:**

- User must be logged in.
- User must pass `AdminMiddleware`.
- Admin can manage agencies, users, bookings, packages, payments, reports, hotels, transport, stories, experiences, page heroes, and settings.

### Agency Authentication

Agency routes:

```php
GET  /agency/register
POST /agency/register
GET  /agency/login
POST /agency/login
POST /agency/logout
GET  /agency/dashboard
```

Agency protected routes use:

```php
Route::middleware(['auth:agency', 'agency.approved'])
```

**Meaning:**

- Agency login uses a separate `agency` guard.
- Agency must be approved before dashboard/package management access.
- Pending, rejected, or suspended agencies cannot log in to protected area.

---

## API Routes

Defined in `routes/api.php`.

```php
POST /api/login

Protected by auth:sanctum:
POST /api/logout
GET  /api/user
```

**Interview explanation:**

The API part is currently small and focused on authentication. It uses Laravel Sanctum token-based authentication. After login, the user can call protected routes using the token.

---

## Core Models and Relationships

### User

File: `app/Models/User.php`

Important fields:

- `name`
- `email`
- `password`
- `role`
- `is_admin`
- `status`
- `provider`
- `provider_id`
- `avatar`

Relationship:

```php
User hasMany Booking
```

### Agency

File: `app/Models/Agency.php`

Important fields:

- `name`
- `contact_person`
- `email`
- `phone`
- `password`
- `logo_path`
- `status`
- `registered_at`
- `approved_at`
- `rejected_at`

Relationships:

```php
Agency hasMany Package
Agency hasMany Booking
```

### Package

File: `app/Models/Package.php`

Important fields:

- `agency_id`
- `name`
- `category`
- `price`
- `duration`
- `location`
- `capacity`
- `booked`
- `status`
- `is_featured`
- `featured_order`
- `image_url`

Relationships:

```php
Package belongsTo Agency
Package hasMany Booking
```

### Booking

File: `app/Models/Booking.php`

Important fields:

- `user_id`
- `agency_id`
- `package_id`
- `booking_reference`
- `package_name`
- `travel_date`
- `amount`
- `discount_amount`
- `coupon_code`
- `currency`
- `status`

Relationships:

```php
Booking belongsTo User
Booking belongsTo Agency
Booking belongsTo Package
Booking hasMany Payment
```

### Payment

File: `app/Models/Payment.php`

Important fields:

- `user_id`
- `booking_id`
- `amount`
- `currency`
- `payment_method`
- `transaction_id`
- `status`
- `gateway_response`

Relationships:

```php
Payment belongsTo User
Payment belongsTo Booking
Payment hasMany PaymentLog
```

### Other Content Models

| Model | Purpose |
|---|---|
| `Hotel` | Destination/hotel listing |
| `Transport` | Transport management |
| `Coupon` | Discount code management |
| `Experience` | Public experience cards |
| `Story` | Travel story content |
| `HomeContent` | Homepage editable content |
| `PageHero` | Hero section content per page |
| `Setting` | Site settings |
| `AdminNotification` | Admin notification records |
| `PaymentLog` | Gateway request/response logs |

---

## Public Page Data Flow

### Home Page

Controller: `PageController@home`

Flow:

```text
Visitor opens /
    ↓
PageController loads HomeContent
    ↓
Loads featured active hotels
    ↓
Loads featured non-draft packages with agency
    ↓
Loads active experiences and stories
    ↓
Returns resources/views/home.blade.php
```

Important logic:

- Featured hotels/packages are shown first.
- If no featured item exists, latest active records are used as fallback.
- Public pages only show active/non-draft content.

---

## Booking Flow

Controller: `BookingController`

Main route:

```php
POST /bookings/from-package
```

Workflow:

```text
Customer selects package
    ↓
Customer submits booking form
    ↓
Controller validates package, amount, travel date, coupon
    ↓
System finds package and agency
    ↓
Coupon discount is calculated
    ↓
Unique booking reference is generated, example: BK-ABC123
    ↓
Booking is saved with status pending
    ↓
Customer redirects to payment checkout
```

Important methods:

- `storeFromPackage()` creates the booking.
- `updateCoupon()` changes/removes coupon.
- `generateReference()` creates unique booking reference.
- `applyCoupon()` validates and calculates discount.
- `authorizeBooking()` prevents users from editing other users' bookings.

---

## Coupon Logic

Coupon validation checks:

```text
Coupon code exists
Coupon is active
Coupon is not expired
```

Discount types:

- `fixed`: subtracts fixed BDT amount.
- `percentage`: calculates percentage discount.

Safety:

- Fixed discount cannot exceed booking amount.
- Invalid coupon returns validation error.

---

## Payment Flow

Controller: `PaymentController`

Main routes:

```php
GET  /payment/checkout/{booking}
POST /payment/initiate
POST /payment/success
POST /payment/fail
POST /payment/cancel
POST /payment/ipn
GET  /payment/invoice/{payment}
```

Workflow:

```text
Customer opens checkout
    ↓
System shows booking and latest payment
    ↓
Customer selects payment method
    ↓
System creates Payment record with pending status
    ↓
If demo and not production: mark payment success directly
    ↓
Otherwise request ShurjoPay token
    ↓
Create ShurjoPay payment request
    ↓
Redirect customer to gateway checkout URL
    ↓
Gateway sends success/fail/cancel/IPN callback
    ↓
System verifies payment with ShurjoPay
    ↓
If valid: payment success and booking confirmed
    ↓
Confirmation email job is dispatched
```

Security checks:

- User can only access own booking/payment unless admin.
- Gateway callback is verified before confirming payment.
- Amount and currency are compared before success.
- Gateway request/response is stored in `PaymentLog`.

---

## ShurjoPay Service

File: `app/Services/Payments/ShurjoPayService.php`

Main methods:

```php
getToken()
createPayment(array $payload)
verifyPayment(array $payload)
```

Environment behavior:

- Sandbox URL when `services.shurjopay.sandbox = true`
- Production URL when sandbox is false

**Interview explanation:**

Payment code is separated into a service class so the controller does not directly contain all HTTP gateway details. This makes payment logic easier to test, replace, and maintain.

---

## Admin Panel Features

Admin base route:

```php
/admin/*
```

Admin can manage:

- Dashboard and pending agencies
- Agencies and approval status
- Users
- Bookings and booking status
- Packages
- Payments and payment details
- Reports with date/search filters and CSV export
- Site settings
- Homepage content
- Page heroes
- Experiences
- Stories
- Hotels
- Transport

### Admin Reports

Controller: `AdminController@reports`

Report supports:

- Date ranges: today, week, 7d, 30d, month, year, all, custom
- Search by transaction, booking reference, package, agency, user
- Booking status counts
- Payment method counts
- Revenue trend
- Booking trend
- CSV export

---

## Agency Panel Features

Agency can:

- Register with business information.
- Wait for admin approval.
- Login after approval.
- View agency dashboard.
- Create, edit, delete own packages.

Important safety:

```php
Package::where('agency_id', $agency->id)->findOrFail($package)
```

This ensures one agency cannot update another agency's package.

---

## Middleware Analysis

| Middleware | Purpose |
|---|---|
| `auth` | User must be logged in |
| `admin` | User must be admin |
| `auth:agency` | Agency must be logged in |
| `agency.approved` | Agency status must be approved |
| `throttle:login` | Limits login attempts |
| `auth:sanctum` | API token authentication |

---

## Database Design Summary

Important tables:

- `users`
- `agencies`
- `packages`
- `bookings`
- `payments`
- `payment_logs`
- `coupons`
- `hotels`
- `transports`
- `experiences`
- `stories`
- `home_contents`
- `page_heroes`
- `settings`
- `admin_notifications`
- Spatie permission tables

Core relationship chain:

```text
User -> Booking -> Payment
Agency -> Package -> Booking
Package -> Booking -> Payment
```

---

## Strengths of This Backend

- Clear Laravel MVC structure.
- Separate public, customer, admin, and agency areas.
- Eloquent relationships are simple and interview-friendly.
- Payment gateway logic is separated into a service class.
- Booking/payment authorization checks are included.
- Admin reporting includes filters, aggregations, and CSV export.
- Agency approval workflow is realistic for travel marketplace projects.
- Demo payment mode helps local testing.

---

## Possible Improvements

These are good interview discussion points:

- Add full feature tests for booking and payment.
- Add policies for `Booking`, `Package`, and admin resources.
- Add stronger enum usage for statuses.
- Add database transactions around booking/payment updates.
- Add event/listener pattern for payment success.
- Add API endpoints for packages/bookings if building mobile app.
- Add queue configuration and retry handling for emails.
- Add more validation for package capacity and booked seats.

---

## Interview Answer: Explain Your Backend

You can say:

> My project is a Laravel 10 based travel booking platform called TravelNest. It follows MVC architecture. Public users can browse travel content and packages. Customers can register, book packages, apply coupons, and complete payment through ShurjoPay or demo payment. Admin users manage agencies, bookings, payments, reports, hotels, transport, stories, experiences, and settings. Agencies have a separate authentication guard and must be approved by admin before they can manage their own packages. The backend uses Eloquent relationships between users, agencies, packages, bookings, and payments. Payment gateway logic is separated into a service class, and callbacks are verified before confirming bookings.

