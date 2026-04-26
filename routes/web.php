<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAgencyController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminPackageController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminTransportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/destinations', [PageController::class, 'destinations'])->name('destinations');
Route::get('/packages', [PageController::class, 'packages'])->name('packages');
Route::get('/experiences', [PageController::class, 'experiences'])->name('experiences');
Route::get('/stories', [PageController::class, 'stories'])->name('stories');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', [RegistrationController::class, 'show'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [RegistrationController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/bookings/from-package', [BookingController::class, 'storeFromPackage'])
        ->name('bookings.from-package');
    Route::put('/bookings/{booking}/coupon', [BookingController::class, 'updateCoupon'])
        ->name('bookings.coupon');
    Route::get('/payment/checkout/{booking}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/initiate', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
});

Route::post('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/fail', [PaymentController::class, 'fail'])->name('payment.fail');
Route::post('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/payment/ipn', [PaymentController::class, 'ipn'])->name('payment.ipn');

// Admin Auth
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Admin Panel
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/agencies', [AdminAgencyController::class, 'index'])->name('admin.agencies');
    Route::get('/agencies/export', [AdminAgencyController::class, 'export'])->name('admin.agencies.export');
    Route::post('/agencies', [AdminAgencyController::class, 'store'])->name('admin.agencies.store');
    Route::put('/agencies/{agency}', [AdminAgencyController::class, 'update'])->name('admin.agencies.update');
    Route::delete('/agencies/{agency}', [AdminAgencyController::class, 'destroy'])->name('admin.agencies.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/export', [AdminUserController::class, 'export'])->name('admin.users.export');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings');
    Route::get('/bookings/export', [AdminBookingController::class, 'export'])->name('admin.bookings.export');
    Route::put('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('admin.bookings.status');

    Route::get('/packages', [AdminPackageController::class, 'index'])->name('admin.packages');
    Route::post('/packages', [AdminPackageController::class, 'store'])->name('admin.packages.store');
    Route::put('/packages/{package}', [AdminPackageController::class, 'update'])->name('admin.packages.update');
    Route::delete('/packages/{package}', [AdminPackageController::class, 'destroy'])->name('admin.packages.destroy');
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications');
    Route::post('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllRead'])
        ->name('admin.notifications.markAll');
    Route::patch('/notifications/{notification}/read', [AdminNotificationController::class, 'markRead'])
        ->name('admin.notifications.read');
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('admin.payments');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
    Route::resource('/hotels', AdminHotelController::class)->names('admin.hotels');
    Route::resource('/transport', AdminTransportController::class)->names('admin.transport');
    Route::get('/{page}', [AdminController::class, 'placeholder'])->name('admin.placeholder');
});
