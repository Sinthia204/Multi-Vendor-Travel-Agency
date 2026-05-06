Summary of changes for Admin Reports

Files modified

- app/Http/Controllers/AdminController.php
  - Reworked `reports()` to be fully dynamic with date ranges (today, week, 7d, 30d, month, year, custom), search, aggregations, eager loading, pagination, and CSV export. Extended `buildTrends()` for additional ranges.

- routes/web.php
  - Added routes: `admin.bookings.show` and `admin.payments.show`.

- app/Http/Controllers/AdminPaymentController.php
  - Added `show()` to display payment details with related booking/package/agency/user/logs.

- app/Http/Controllers/AdminBookingController.php
  - Added `show()` to display booking details with related user/agency/package/payments.

- resources/views/admin/reports.blade.php
  - Replaced static report with dynamic filters, responsive layout, summary cards grid, charts, and two updated tables:
    - Recent Payments: Transaction ID, Booking ID, Customer Name, Agency Name, Package Name, Payment Method, Amount, Status, Payment Date, Action.
    - Recent Bookings: Booking ID, Customer Name, Agency Name, Package Name, Travel Date, Total Amount, Booking Status, Payment Status, Booking Date, Action.
  - Implemented search, pagination, CSV export link and safer customer display logic (admin users excluded unless they made the booking).
  - Table containers use `.tn-table-wrap` to localize horizontal scrolling.

- resources/views/admin/payments/show.blade.php
  - New payment detail view.

- resources/views/admin/bookings/show.blade.php
  - New booking detail view.

- public/css/custom.css
  - Added global overflow-x:hidden, responsive filter and summary-grid styles, chart scaling rules, `.tn-table-wrap` behavior, sticky table headers, responsive table-cell wrapping, and breakpoint adjustments.

How to test

1. Start dev server:

   php artisan serve --port=8000

2. Login as an admin user and visit:

   http://127.0.0.1:8000/admin/reports

3. Verify functionality:
   - Change date range (Today, This week, Last 7 days, Last 30 days, This month, This year, All time).
   - Use custom `from`/`to` dates.
   - Search by booking reference, transaction id, customer name, agency name, or package name.
   - Click "Export CSV" to download recent payments for the applied filters.
   - Click "View" on payments/bookings to open detail pages.
   - Use pagination to navigate through records.

4. Responsive checks:
   - Desktop: There must be no horizontal page scrollbar. Tables may scroll inside their containers, not the entire page.
   - Tablet/Mobile: Filters should stack; summary cards should be 2/1 per row; chart areas and tables should fit or scroll inside their containers.

Notes & next steps

- PDF export is not implemented (CSV is available). I can add PDF export if you want — it requires adding a composer package (dompdf or snappy) and a small controller handler.
- If you prefer sticky table headers to be offset under the card header, I can adjust top values per card header height.
- I added minimal small-screen paddings; further UI tweaks can be done based on screenshots or your visual preference.

If you'd like, I can now:
- Add PDF export.
- Tweak visual spacing or make headers offset for sticky behavior.
- Run a set of screenshots at different viewport sizes and provide them.
