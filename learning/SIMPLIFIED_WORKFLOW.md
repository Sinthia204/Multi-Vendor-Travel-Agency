# TravelNest Simplified Workflow

## System Overview

**TravelNest** is a travel booking marketplace. Visitors browse hotels and tour packages, customers book packages and pay, agencies publish travel packages after admin approval, and admin controls the full system.

```text
Visitor
  ↓
Browse public travel pages
  ↓
Register/Login as customer
  ↓
Book package
  ↓
Apply coupon
  ↓
Make payment
  ↓
Booking confirmed
```

---

## Main Actors

| Actor | What They Do |
|---|---|
| Visitor | Browse home, destinations, packages, experiences, stories, contact |
| Customer/User | Register, login, book package, pay, download invoice |
| Agency | Register, wait for approval, login, manage own packages |
| Admin | Manage whole platform, approve agencies, manage content, bookings, payments, reports |

---

## 1. Public Website Workflow

```text
Open website
  ↓
Home page loads featured hotels and packages
  ↓
Visitor views destinations/packages/experiences/stories
  ↓
Visitor searches packages by destination
  ↓
Visitor chooses a package
  ↓
Login/register required before booking
```

Important files:

- `routes/web.php`
- `app/Http/Controllers/PageController.php`
- `resources/views/home.blade.php`
- `resources/views/packages.blade.php`
- `resources/views/destinations.blade.php`

Backend logic:

- Active hotels are shown in destinations.
- Non-draft packages are shown in packages.
- Featured items appear on homepage.
- `PageHero` controls hero content for pages.

---

## 2. Customer Registration/Login Workflow

```text
Customer opens register page
  ↓
Submits name, email, password
  ↓
Laravel validates input
  ↓
Password is hashed
  ↓
User record is created
  ↓
Customer can login
```

Login flow:

```text
Customer enters email/password
  ↓
Laravel attempts authentication
  ↓
Session is created
  ↓
Customer can access booking/payment routes
```

Important files:

- `RegistrationController`
- `AuthController`
- `Auth/SocialiteController`
- `User` model
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`

---

## 3. Package Booking Workflow

```text
Customer selects package
  ↓
Submits booking form
  ↓
BookingController validates data
  ↓
System loads selected Package and Agency
  ↓
Amount is calculated from package price
  ↓
Coupon is checked if provided
  ↓
Unique booking reference is generated
  ↓
Booking status is saved as pending
  ↓
Customer redirects to checkout page
```

Main route:

```php
POST /bookings/from-package
```

Important controller:

```php
BookingController@storeFromPackage
```

Main database change:

```text
New row created in bookings table
```

Booking status:

- `pending`: booking created, payment not confirmed.
- `confirmed`: payment/admin confirmation completed.
- `cancelled`: booking cancelled by admin or flow.

---

## 4. Coupon Workflow

```text
Customer enters coupon code
  ↓
System converts code to uppercase
  ↓
Find active coupon
  ↓
Check expiry date
  ↓
Calculate fixed or percentage discount
  ↓
Update booking discount_amount and coupon_code
```

Main route:

```php
PUT /bookings/{booking}/coupon
```

Important methods:

- `applyCoupon()`
- `updateCoupon()`

Interview point:

> Coupon calculation is handled in the booking controller. It supports fixed and percentage discounts, rejects inactive or expired coupons, and prevents fixed discount from becoming larger than the booking amount.

---

## 5. Payment Workflow

```text
Customer opens checkout
  ↓
Selects payment method
  ↓
PaymentController creates pending payment
  ↓
If demo payment in local: mark success immediately
  ↓
If real payment: request ShurjoPay token
  ↓
Send payment creation request to ShurjoPay
  ↓
Redirect user to gateway
  ↓
Gateway sends callback
  ↓
System verifies transaction
  ↓
Payment success
  ↓
Booking status becomes confirmed
  ↓
Confirmation email job is dispatched
```

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

Important files:

- `PaymentController`
- `ShurjoPayService`
- `Payment` model
- `PaymentLog` model
- `SendPaymentConfirmationJob`
- `resources/views/payments/checkout.blade.php`
- `resources/views/payments/invoice.blade.php`

Important safety:

- User must own the booking or be admin.
- Gateway callback is verified.
- Amount and currency are checked.
- Gateway request/response is logged.

---

## 6. Invoice Workflow

```text
Payment successful
  ↓
Customer clicks invoice
  ↓
System loads Payment, Booking, User
  ↓
Authorization check runs
  ↓
Blade invoice view renders
  ↓
DomPDF generates PDF
  ↓
PDF downloads
```

Main route:

```php
GET /payment/invoice/{payment}
```

Interview point:

> The invoice is generated dynamically using DomPDF from a Blade view, so the invoice layout can be maintained like normal Laravel views.

---

## 7. Agency Registration and Approval Workflow

```text
Agency opens registration page
  ↓
Submits agency name, contact person, email, phone, password, logo
  ↓
Laravel validates data
  ↓
Logo is stored in public disk if uploaded
  ↓
Agency record is created with status pending
  ↓
Admin reviews agency
  ↓
Admin approves/rejects/suspends
  ↓
Only approved agencies can login to dashboard
```

Important files:

- `AgencyAuthController`
- `Agency` model
- `AdminAgencyController`
- `EnsureAgencyApproved` middleware

Agency statuses:

- `pending`
- `approved`
- `rejected`
- `suspended`

---

## 8. Agency Package Management Workflow

```text
Approved agency logs in
  ↓
Opens agency dashboard
  ↓
Views own packages
  ↓
Creates/updates/deletes packages
  ↓
Package appears publicly if status is active
```

Important route group:

```php
Route::middleware(['auth:agency', 'agency.approved'])
```

Important safety:

```text
Agency can only query packages where agency_id = logged-in agency id
```

Interview point:

> I used a separate agency guard and filtered package queries by `agency_id`, so agency accounts are isolated from each other.

---

## 9. Admin Workflow

```text
Admin logs in
  ↓
AdminMiddleware checks permission
  ↓
Admin dashboard opens
  ↓
Admin manages agencies/users/bookings/packages/content/payments/reports
```

Admin features:

- Dashboard with pending agencies.
- Agency approval/rejection.
- User management.
- Booking search/filter/status update/export.
- Payment list/details.
- Reports with revenue and booking trends.
- Hotel and transport CRUD.
- Experience and story CRUD.
- Homepage and page hero content management.
- Settings management.

Important files:

- `AdminController`
- `AdminAgencyController`
- `AdminBookingController`
- `AdminPaymentController`
- `AdminPackageController`
- `AdminHotelController`
- `AdminTransportController`
- `AdminExperienceController`
- `AdminStoryController`
- `SettingsController`

---

## 10. Reports Workflow

```text
Admin opens reports page
  ↓
Selects date range/search
  ↓
System filters payments, bookings, users
  ↓
Calculates totals and trends
  ↓
Shows report table and charts
  ↓
Admin can export CSV
```

Report filters:

- Today
- Week
- Last 7 days
- Last 30 days
- Month
- Year
- All
- Custom range
- Search text

Calculated metrics:

- Total bookings
- Pending/confirmed/cancelled bookings
- Total payments
- Successful/failed payments
- Total revenue
- Monthly revenue
- Total agencies
- Total packages
- Total customers
- Revenue trend
- Booking trend

---

## 11. Content Management Workflow

Admin manages public website content:

```text
Admin changes hotel/package/story/experience/page hero
  ↓
Data saved in database
  ↓
Public PageController loads active records
  ↓
Website updates dynamically
```

Content modules:

- Hotels
- Transport
- Experiences
- Stories
- Home content
- Page heroes
- Settings

---

## 12. Full Project Flow in One Diagram

```text
Public Visitor
  ├── Browse Home/Destinations/Packages
  ├── Read Experiences/Stories
  └── Register/Login

Customer
  ├── Select Package
  ├── Create Booking
  ├── Apply Coupon
  ├── Pay via Demo/ShurjoPay
  ├── Booking Confirmed
  └── Download Invoice

Agency
  ├── Register
  ├── Wait for Admin Approval
  ├── Login
  └── Manage Own Packages

Admin
  ├── Approve Agencies
  ├── Manage Users/Bookings/Payments
  ├── Manage Packages/Hotels/Transport
  ├── Manage Stories/Experiences/Page Content
  └── View Reports and Export CSV
```

---

## Interview Quick Explanation

You can explain the workflow like this:

> TravelNest has four main workflows: public browsing, customer booking/payment, agency package management, and admin management. Visitors browse active travel content. A customer logs in, books a package, applies a coupon, and pays through demo payment or ShurjoPay. After successful verification, the payment is marked successful and booking becomes confirmed. Agencies register separately and can manage packages only after admin approval. Admin has a protected panel to manage agencies, users, bookings, payments, reports, and website content.

