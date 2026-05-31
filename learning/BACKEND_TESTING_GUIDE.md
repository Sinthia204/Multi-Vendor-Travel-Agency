# TravelNest Backend Testing Guide

## Purpose

This guide helps you test the TravelNest Laravel backend manually and with PHPUnit. It is written for interview preparation, so each section includes what to test, why it matters, and how to explain it.

---

## 1. Setup Checklist

Run these from the project root:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Frontend assets:

```bash
npm run dev
```

Test command:

```bash
php artisan test
```

Useful Laravel commands:

```bash
php artisan route:list
php artisan migrate:fresh --seed
php artisan cache:clear
php artisan config:clear
php artisan queue:work
```

---

## 2. Environment Variables to Check

Open `.env` and verify:

```env
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=log
QUEUE_CONNECTION=sync
```

For ShurjoPay:

```env
SHURJOPAY_SANDBOX=true
SHURJOPAY_USERNAME=your_username
SHURJOPAY_PASSWORD=your_password
SHURJOPAY_STORE_ID=your_store_id
SHURJOPAY_PREFIX=TN
```

Interview point:

> I keep gateway credentials in environment variables, not hardcoded in controllers. Laravel config reads them through `config/services.php`.

---

## 3. Route Testing

List all routes:

```bash
php artisan route:list
```

Important routes to verify:

```text
GET  /
GET  /destinations
GET  /packages
GET  /experiences
GET  /stories
GET  /contact
GET  /login
POST /login
POST /bookings/from-package
GET  /payment/checkout/{booking}
POST /payment/initiate
GET  /admin/login
GET  /admin/dashboard
GET  /agency/register
GET  /agency/login
GET  /agency/dashboard
```

Expected result:

- Public routes should open without login.
- Booking/payment routes should require customer login.
- Admin routes should require admin login.
- Agency dashboard should require approved agency login.

---

## 4. Manual Public Page Test

### Test: Home Page

Steps:

1. Open `/`.
2. Check featured hotels.
3. Check featured packages.
4. Check experiences and stories.

Expected:

- Page loads without login.
- Only active/allowed content appears.
- If no featured records exist, latest active records show as fallback.

Related code:

- `PageController@home`
- `Hotel`
- `Package`
- `Experience`
- `Story`
- `HomeContent`

### Test: Packages Page Search

Steps:

1. Open `/packages`.
2. Search by destination/location/category.
3. Confirm list changes.

Expected:

- Draft packages are hidden.
- Package agency information loads with package.

Related code:

- `PageController@packages`
- `Package::with('agency')`

---

## 5. Customer Authentication Test

### Register

Steps:

1. Open `/register`.
2. Submit valid name, email, password.
3. Try duplicate email.

Expected:

- Valid user is created.
- Duplicate email is rejected.
- Password is hashed.

### Login

Steps:

1. Open `/login`.
2. Submit correct credentials.
3. Submit wrong password.

Expected:

- Correct login creates session.
- Wrong login shows validation error.
- Login route is throttled.

Related code:

- `RegistrationController`
- `AuthController`
- `User` model
- `routes/web.php`

---

## 6. Booking Test

### Test: Create Booking from Package

Precondition:

- User is logged in.
- At least one active package exists.

Steps:

1. Open `/packages`.
2. Select a package.
3. Submit booking form with travel date.
4. Submit optional coupon.

Expected database result in `bookings` table:

```text
user_id = logged in user
agency_id = package agency
package_id = selected package
booking_reference = unique BK-XXXXXX value
amount = package price
discount_amount = coupon discount or 0
currency = BDT
status = pending
```

Expected UI result:

- User redirects to payment checkout page.

Related code:

- `BookingController@storeFromPackage`
- `Booking` model
- `Package` model
- `Coupon` model

---

## 7. Coupon Test

### Valid Fixed Coupon

Example:

```text
Booking amount: 10000
Coupon type: fixed
Coupon value: 1000
Expected discount: 1000
```

### Valid Percentage Coupon

Example:

```text
Booking amount: 10000
Coupon type: percentage
Coupon value: 10
Expected discount: 1000
```

### Invalid Coupon

Test cases:

- Code does not exist.
- Coupon inactive.
- Coupon expired.

Expected:

- Booking is not updated with invalid coupon.
- User sees error message.

Related method:

```php
BookingController@applyCoupon
```

Interview point:

> I test both positive and negative coupon paths because discount logic directly affects payment amount.

---

## 8. Payment Test

### Demo Payment Test

Precondition:

- `APP_ENV` is not production.
- User has a pending booking.

Steps:

1. Open checkout page.
2. Select `demo` payment method.
3. Submit payment.

Expected:

- Payment record is created.
- Payment status becomes `success`.
- Booking status becomes `confirmed`.
- Payment log is created.
- Confirmation email job is dispatched.

Related code:

- `PaymentController@initiatePayment`
- `Payment` model
- `PaymentLog` model
- `SendPaymentConfirmationJob`

### ShurjoPay Payment Test

Steps:

1. Configure ShurjoPay sandbox credentials.
2. Select non-demo payment method.
3. Submit payment.
4. Confirm redirect to gateway.
5. Complete or cancel payment.

Expected:

- Token request is sent.
- Payment creation request is sent.
- Customer redirects to ShurjoPay checkout.
- Callback updates payment status after verification.

Related service:

```php
ShurjoPayService
```

---

## 9. Payment Callback Test

Callback routes:

```text
POST /payment/success
POST /payment/fail
POST /payment/cancel
POST /payment/ipn
```

Success expected:

```text
Payment status = success
Booking status = confirmed
Gateway response saved
PaymentLog record saved
```

Fail/cancel expected:

```text
Payment status = failed
Booking remains pending or not confirmed
User sees retry message
```

Security checks:

- Unknown transaction ID should not confirm anything.
- Verification failure should mark payment failed.
- Amount mismatch should fail.
- Currency mismatch should fail.

---

## 10. Invoice Test

Steps:

1. Complete a successful payment.
2. Open invoice route.
3. Download PDF.

Expected:

- PDF downloads with transaction, booking, and customer information.
- User cannot download another user's invoice unless admin.

Related code:

- `PaymentController@invoice`
- `resources/views/payments/invoice.blade.php`
- DomPDF package

---

## 11. Agency Test

### Agency Registration

Steps:

1. Open `/agency/register`.
2. Submit agency details.
3. Upload logo.

Expected:

- Agency status is `pending`.
- Logo is stored in `storage/app/public/agencies`.
- Agency cannot access dashboard yet.

### Agency Approval

Steps:

1. Login as admin.
2. Open agencies page.
3. Approve agency.

Expected:

- Agency status becomes `approved`.
- `approved_at` is set.
- Agency can login.

### Agency Package CRUD

Steps:

1. Login as approved agency.
2. Create package.
3. Edit package.
4. Delete package.

Expected:

- Package `agency_id` equals logged-in agency id.
- Agency cannot access other agency's package.

Related code:

- `AgencyAuthController`
- `AgencyPackageController`
- `AdminAgencyController`
- `EnsureAgencyApproved`

---

## 12. Admin Panel Test

Test admin modules:

| Module | What to Test |
|---|---|
| Dashboard | Pending agencies list |
| Agencies | Search, filter, create, update, approve, reject, delete, export |
| Users | Create, update, delete, export |
| Bookings | Search, filter, show, status update, export |
| Payments | List and detail page |
| Reports | Date range, search, totals, trends, CSV export |
| Packages | Admin package management |
| Hotels | CRUD |
| Transport | CRUD |
| Experiences | CRUD |
| Stories | CRUD |
| Settings | Update site settings |
| Page Heroes | Update page hero content |

Expected:

- Non-admin users cannot access `/admin/*`.
- Admin actions validate input.
- List pages paginate properly.

---

## 13. API Test

### API Login

Request:

```http
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

Expected:

- Returns token or auth response.
- Wrong credentials fail.

### Protected API User

Request:

```http
GET /api/user
Authorization: Bearer TOKEN
```

Expected:

- Returns current user through `UserResource`.
- Without token returns unauthenticated error.

Related code:

- `routes/api.php`
- `App\Http\Controllers\Api\AuthController`
- `UserResource`

---

## 14. Suggested PHPUnit Feature Tests

Create tests for:

```text
Public pages load successfully
User can register
User can login
Guest cannot create booking
Authenticated user can create booking
Invalid coupon is rejected
Valid coupon updates discount
Demo payment confirms booking
User cannot view another user's checkout
Admin can access dashboard
Normal user cannot access admin dashboard
Pending agency cannot access dashboard
Approved agency can create package
Agency cannot edit another agency's package
```

Example test idea:

```php
public function test_guest_cannot_create_booking(): void
{
    $package = Package::factory()->create();

    $response = $this->post(route('bookings.from-package'), [
        'package_id' => $package->id,
        'package_name' => $package->name,
        'amount' => $package->price,
    ]);

    $response->assertRedirect(route('login'));
}
```

---

## 15. Testing Interview Questions

### Q: How did you test payment?

Answer:

> I tested payment in two layers. First, I used demo payment in local environment to verify that payment creation, booking confirmation, and invoice generation work. Second, for real gateway flow, I tested ShurjoPay sandbox by checking token generation, payment creation, callback handling, verification, and failure/cancel scenarios.

### Q: How did you ensure users cannot access other users' bookings?

Answer:

> I used authorization checks in booking and payment controllers. The system compares the booking's `user_id` with the logged-in user's id. Admin users are allowed, but normal users get a 403 response.

### Q: What are the most important test cases?

Answer:

> The most important tests are authentication, booking creation, coupon discount calculation, payment success/failure, admin protection, and agency package isolation because these areas affect security and business logic.

### Q: What would you improve in testing?

Answer:

> I would add more feature tests, mock the ShurjoPay HTTP requests, add policy tests, test queue jobs, and add database transaction tests around payment success to make the system more reliable.

---

## 16. Quick Manual Testing Checklist

- [ ] Public home page loads.
- [ ] Packages page filters by destination.
- [ ] Customer can register.
- [ ] Customer can login/logout.
- [ ] Guest cannot book package.
- [ ] Logged-in customer can create booking.
- [ ] Valid coupon applies discount.
- [ ] Invalid coupon shows error.
- [ ] Demo payment confirms booking.
- [ ] Invoice PDF downloads.
- [ ] Customer cannot access another customer's booking.
- [ ] Agency can register.
- [ ] Pending agency cannot access dashboard.
- [ ] Admin can approve agency.
- [ ] Approved agency can manage own packages.
- [ ] Agency cannot edit another agency package.
- [ ] Admin can manage bookings.
- [ ] Admin reports calculate revenue.
- [ ] CSV exports download.
- [ ] API login works.
- [ ] `/api/user` requires token.

