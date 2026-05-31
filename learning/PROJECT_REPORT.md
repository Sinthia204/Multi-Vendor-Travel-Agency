# TravelNest Project Report

## 1. Project Title

**TravelNest - Travel Booking and Agency Management System**

---

## 2. Project Overview

TravelNest is a Laravel based travel booking web application. The platform allows visitors to browse travel packages, hotels, destinations, experiences, and travel stories. Registered customers can book packages, apply coupons, make payments, and download invoices. Travel agencies can register and, after admin approval, manage their own packages. Admin can control the entire platform from a protected dashboard.

The project demonstrates practical Laravel backend development with MVC architecture, authentication, authorization, Eloquent relationships, payment gateway integration, admin reporting, agency approval workflow, and dynamic content management.

---

## 3. Objectives

The main objectives of TravelNest are:

- Build a travel marketplace where users can discover travel packages.
- Allow customers to create bookings and complete payments.
- Provide admin control over agencies, users, bookings, payments, reports, and content.
- Allow agencies to register and manage packages after approval.
- Support coupon discounts.
- Generate invoices as PDF.
- Keep payment gateway logic organized through a service class.
- Prepare a real-world project structure that can be explained in interviews.

---

## 4. Technology Used

| Category | Technology |
|---|---|
| Backend | Laravel 10 |
| Language | PHP 8.1+ |
| Database | MySQL or configured Laravel database |
| ORM | Eloquent |
| Frontend Template | Blade |
| Authentication | Laravel Auth, Sanctum, Socialite, agency guard |
| Authorization | Middleware, Spatie Permission |
| Payment | ShurjoPay, local demo payment |
| PDF | DomPDF |
| Queue | Laravel Jobs |
| Testing | PHPUnit |
| Package Manager | Composer, npm |

---

## 5. Main Users

### Visitor

Visitor can:

- Browse homepage.
- View destinations and hotels.
- View travel packages.
- Read experiences and stories.
- Visit contact page.
- Register or login.

### Customer

Customer can:

- Login/register.
- Book travel packages.
- Apply coupon.
- Make payment.
- Download invoice.

### Agency

Agency can:

- Register with agency details.
- Wait for admin approval.
- Login after approval.
- Create, edit, delete own packages.

### Admin

Admin can:

- Login to admin panel.
- Approve/reject agencies.
- Manage users.
- Manage bookings.
- Manage payments.
- Manage packages.
- Manage hotels and transport.
- Manage experiences and stories.
- Manage homepage/page hero content.
- View reports and export CSV.

---

## 6. Main Features

### Public Website

- Homepage with featured hotels, packages, experiences, and stories.
- Destinations page.
- Packages page with destination search.
- Experiences page.
- Stories page.
- Contact page.

### Customer Features

- Customer registration.
- Customer login/logout.
- Social login support.
- Package booking.
- Coupon discount.
- Checkout page.
- Payment processing.
- Invoice PDF download.

### Agency Features

- Agency registration.
- Agency login/logout.
- Approval status validation.
- Agency dashboard.
- Own package CRUD.

### Admin Features

- Admin login.
- Dashboard.
- Agency management and approval.
- User management.
- Booking management and status update.
- Payment tracking.
- Reports with revenue and booking trends.
- CSV export.
- Hotel CRUD.
- Transport CRUD.
- Package management.
- Story and experience management.
- Settings management.
- Home content and page hero management.

---

## 7. MVC Architecture Explanation

TravelNest follows Laravel MVC architecture.

```text
Route receives request
  ↓
Controller handles business flow
  ↓
Model communicates with database
  ↓
Controller sends data to Blade view
  ↓
User sees response
```

Example:

```text
GET /packages
  ↓
PageController@packages
  ↓
Package model queries non-draft packages
  ↓
resources/views/packages.blade.php
```

Interview answer:

> I used Laravel MVC to separate responsibilities. Routes define URLs, controllers handle request logic, models represent database tables and relationships, and Blade views display data to users.

---

## 8. Database Design

### Important Tables

| Table | Purpose |
|---|---|
| `users` | Customer/admin accounts |
| `agencies` | Travel agency accounts |
| `packages` | Tour package records |
| `bookings` | Customer package bookings |
| `payments` | Payment transaction records |
| `payment_logs` | Gateway request/response logs |
| `coupons` | Discount codes |
| `hotels` | Destination/hotel content |
| `transports` | Transport content |
| `experiences` | Public experience content |
| `stories` | Public travel stories |
| `home_contents` | Editable homepage data |
| `page_heroes` | Hero sections for pages |
| `settings` | Website settings |
| `admin_notifications` | Admin notification data |
| Spatie tables | Roles and permissions |

### Core Relationships

```text
User has many Bookings
Agency has many Packages
Agency has many Bookings
Package belongs to Agency
Package has many Bookings
Booking belongs to User
Booking belongs to Agency
Booking belongs to Package
Booking has many Payments
Payment belongs to Booking
Payment belongs to User
Payment has many PaymentLogs
```

Simplified ERD:

```text
users
  └── bookings
        ├── packages
        │     └── agencies
        └── payments
              └── payment_logs
```

---

## 9. Authentication and Authorization

### Customer Auth

Customers use normal Laravel session authentication. They can login, logout, register, and use protected booking/payment routes.

### Admin Auth

Admin routes are protected with:

```php
['auth', 'admin']
```

Admin access is checked by middleware using role/admin fields.

### Agency Auth

Agencies use a separate authentication guard:

```php
auth:agency
```

Agency dashboard also requires:

```php
agency.approved
```

This prevents pending/rejected agencies from accessing package management.

---

## 10. Booking Module

Booking is handled mainly by `BookingController`.

### Booking Creation Flow

```text
User selects package
  ↓
User submits booking request
  ↓
Validate package_id, package_name, amount, travel_date, coupon_code
  ↓
Load selected Package and Agency
  ↓
Apply coupon if available
  ↓
Generate unique booking reference
  ↓
Create booking with pending status
  ↓
Redirect to checkout
```

Important booking fields:

- `booking_reference`
- `package_name`
- `amount`
- `discount_amount`
- `coupon_code`
- `currency`
- `status`

Business rule:

> A booking is first created as `pending`. It becomes `confirmed` only after successful payment or admin update.

---

## 11. Coupon Module

Coupon logic supports:

- Fixed discount.
- Percentage discount.
- Active/inactive validation.
- Expiry date validation.

Example:

```text
Package price: 12000 BDT
Coupon: 10%
Discount: 1200 BDT
Payable amount: 10800 BDT
```

Important safety:

- Invalid coupons are rejected.
- Expired coupons are rejected.
- Fixed discount cannot exceed booking amount.

---

## 12. Payment Module

Payment is handled by `PaymentController` and `ShurjoPayService`.

### Payment Flow

```text
Checkout page
  ↓
Create pending payment
  ↓
Demo payment or ShurjoPay payment
  ↓
Gateway callback
  ↓
Verify transaction
  ↓
Mark payment success/failed
  ↓
Update booking status
  ↓
Dispatch confirmation email job
```

### Demo Payment

In local/non-production environment, demo payment can instantly mark payment as successful. This helps development and testing without real gateway dependency.

### ShurjoPay Payment

ShurjoPay integration has three major steps:

1. Get token.
2. Create payment.
3. Verify payment callback.

Important file:

```text
app/Services/Payments/ShurjoPayService.php
```

Interview answer:

> I separated ShurjoPay API calls into a service class. The controller creates payment records and handles application flow, while the service handles external gateway communication.

---

## 13. Admin Module

Admin panel is the control center of the system.

### Admin Capabilities

- View dashboard.
- Approve or reject agencies.
- Manage users.
- View and update bookings.
- View payments.
- Generate reports.
- Export CSV files.
- Manage travel content.
- Manage settings.

### Report Module

Reports include:

- Total bookings.
- Pending bookings.
- Confirmed bookings.
- Cancelled bookings.
- Total payments.
- Successful payments.
- Failed payments.
- Total revenue.
- Monthly revenue.
- Payment method counts.
- Booking status counts.
- Revenue trend.
- Booking trend.

Date filters:

- Today
- Week
- 7 days
- 30 days
- Month
- Year
- All
- Custom

---

## 14. Agency Module

Agency module creates a marketplace-style system.

### Agency Flow

```text
Agency registers
  ↓
Status = pending
  ↓
Admin approves agency
  ↓
Agency logs in
  ↓
Agency manages own packages
```

Security rule:

```text
Agency can only access packages where package.agency_id = logged-in agency id
```

Interview answer:

> Agency accounts are separated from normal users using a dedicated guard. Admin approval is required before access, and package queries are scoped by agency id.

---

## 15. Content Management

Admin can update public website content through database-driven modules.

Content modules:

- Hotels
- Transport
- Experiences
- Stories
- Homepage content
- Page heroes
- Settings

Benefit:

> Admin can update the public website without changing code.

---

## 16. Security Features

Implemented security concepts:

- Password hashing.
- Middleware protected routes.
- Login throttling.
- Separate agency guard.
- Admin middleware.
- Booking ownership check.
- Payment ownership check.
- Payment verification before confirmation.
- Environment variables for gateway credentials.
- CSRF protection for web forms.
- Validation before database write.

Good interview point:

> I used Laravel's built-in security features like CSRF, validation, password hashing, middleware, and authentication guards, then added project-specific authorization checks for bookings, payments, admin access, and agency package ownership.

---

## 17. Validation

Validation is used in controllers and request classes.

Examples:

- User login/register validation.
- Agency registration validation.
- Package create/update validation.
- Booking validation.
- Coupon validation.
- Admin CRUD validation.
- Payment initiation validation.

Why validation matters:

> Validation protects the database from invalid data and gives users clear errors before business logic runs.

---

## 18. File Structure You Should Know

```text
app/Http/Controllers/PageController.php
app/Http/Controllers/BookingController.php
app/Http/Controllers/PaymentController.php
app/Http/Controllers/AdminController.php
app/Http/Controllers/AdminAgencyController.php
app/Http/Controllers/AdminBookingController.php
app/Http/Controllers/AgencyAuthController.php
app/Http/Controllers/AgencyPackageController.php
app/Models/User.php
app/Models/Agency.php
app/Models/Package.php
app/Models/Booking.php
app/Models/Payment.php
app/Services/Payments/ShurjoPayService.php
routes/web.php
routes/api.php
resources/views/
database/migrations/
database/seeders/
```

---

## 19. Project Strengths

- Real-world travel booking idea.
- Clear Laravel MVC structure.
- Multiple user roles/areas.
- Agency approval workflow.
- Booking and coupon system.
- Payment gateway integration.
- PDF invoice generation.
- Admin reports and CSV export.
- Dynamic content management.
- Good interview discussion scope.

---

## 20. Limitations and Future Improvements

Possible improvements:

- Add more automated feature tests.
- Add policies for authorization.
- Use enum classes for statuses.
- Add transactions around payment confirmation.
- Add package capacity validation before booking.
- Add booking cancellation/refund workflow.
- Add REST APIs for mobile app.
- Add customer dashboard for booking history.
- Add notification/email templates.
- Mock ShurjoPay in automated tests.
- Add audit logs for admin actions.

---

## 21. Interview Questions and Answers

### Q1. What is your project about?

TravelNest is a Laravel travel booking platform where customers can browse and book packages, agencies can manage packages after admin approval, and admin can manage the complete platform including bookings, payments, agencies, content, and reports.

### Q2. Which architecture did you use?

I used Laravel MVC architecture. Routes receive requests, controllers handle business logic, models interact with the database using Eloquent, and Blade views display the UI.

### Q3. What are the main modules?

The main modules are public pages, customer authentication, booking, coupon, payment, invoice, admin management, agency management, reports, and content management.

### Q4. How does booking work?

The user selects a package, submits booking data, the system validates the request, calculates amount and coupon discount, creates a unique booking reference, stores the booking as pending, and redirects the user to checkout.

### Q5. How does payment work?

Payment starts by creating a pending payment record. In local environment, demo payment can complete instantly. For real payment, the system gets a ShurjoPay token, creates gateway payment, redirects user, receives callback, verifies the transaction, then marks payment success and booking confirmed.

### Q6. Why did you use a service class for ShurjoPay?

I used a service class to separate external payment API communication from controller logic. This keeps the controller cleaner and makes payment code easier to maintain and test.

### Q7. How did you secure admin routes?

Admin routes are inside an `/admin` route group protected by `auth` and `admin` middleware. Only logged-in admin users can access those routes.

### Q8. How did you secure agency data?

Agencies use a separate guard and must be approved before accessing the dashboard. Package queries are filtered by logged-in agency id, so an agency can only manage its own packages.

### Q9. How do you prevent users from accessing others' payments?

In booking and payment controllers, the system checks whether the logged-in user owns the booking. Admin users are allowed, but normal users are blocked with a 403 error.

### Q10. What was the most challenging part?

The payment flow was the most challenging because it includes local payment records, gateway token generation, redirect, callback, verification, status update, payment logs, and booking confirmation.

### Q11. What would you improve?

I would add more automated tests, use policies for authorization, use enum classes for statuses, add database transactions for payment confirmation, and add a customer dashboard for booking history.

---

## 22. Short Viva/Interview Summary

Use this answer when asked to explain the whole project:

> TravelNest is a Laravel 10 travel booking and agency management system. It has public pages for browsing destinations and packages, customer authentication for booking, a coupon module for discounts, ShurjoPay/demo payment integration, PDF invoice generation, a separate agency guard with admin approval, and an admin panel for managing users, agencies, bookings, payments, reports, and content. The project follows MVC architecture and uses Eloquent relationships between users, agencies, packages, bookings, and payments.

