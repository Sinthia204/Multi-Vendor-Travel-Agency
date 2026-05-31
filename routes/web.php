<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAgencyController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminExperienceController;
use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\AdminHomeContentController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminPageHeroController;
use App\Http\Controllers\AdminPackageController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminStoryController;
use App\Http\Controllers\AdminTransportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AgencyAuthController;
use App\Http\Controllers\AgencyDashboardController;
use App\Http\Controllers\AgencyPackageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\SocialiteController;
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

Route::prefix('agency')->group(function () {
    Route::get('/register', [AgencyAuthController::class, 'showRegister'])
        ->middleware('guest:agency')
        ->name('agency.register');
    Route::post('/register', [AgencyAuthController::class, 'register'])
        ->middleware('guest:agency')
        ->name('agency.register.submit');
    Route::get('/login', [AgencyAuthController::class, 'showLogin'])
        ->middleware('guest:agency')
        ->name('agency.login');
    Route::post('/login', [AgencyAuthController::class, 'login'])
        ->middleware('guest:agency')
        ->name('agency.login.submit');
    Route::post('/logout', [AgencyAuthController::class, 'logout'])
        ->middleware('auth:agency')
        ->name('agency.logout');

    Route::middleware(['auth:agency', 'agency.approved'])->group(function () {
        Route::get('/dashboard', [AgencyDashboardController::class, 'index'])->name('agency.dashboard');
        Route::get('/packages', [AgencyPackageController::class, 'index'])->name('agency.packages.index');
        Route::get('/packages/create', [AgencyPackageController::class, 'create'])->name('agency.packages.create');
        Route::post('/packages', [AgencyPackageController::class, 'store'])->name('agency.packages.store');
        Route::get('/packages/{package}/edit', [AgencyPackageController::class, 'edit'])->name('agency.packages.edit');
        Route::put('/packages/{package}', [AgencyPackageController::class, 'update'])->name('agency.packages.update');
        Route::delete('/packages/{package}', [AgencyPackageController::class, 'destroy'])->name('agency.packages.destroy');
    });
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [RegistrationController::class, 'show'])->name('register');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.submit');
Route::post('/register', [RegistrationController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

Route::middleware('auth')->group(function () {
    Route::post('/bookings/from-package', [BookingController::class, 'storeFromPackage'])
        ->name('bookings.from-package');
    Route::put('/bookings/{booking}/coupon', [BookingController::class, 'updateCoupon'])
        ->name('bookings.coupon');

    // User bookings page - view all bookings
    Route::get('/bookings', function () {
        // Get all bookings for the authenticated user
        $bookings = \App\Models\Booking::where('user_id', auth()->id())
            ->with(['agency', 'payments'])
            ->latest()
            ->get();

        return view('user.bookings.index', compact('bookings'));
    })->name('user.bookings.index');

    // Payment routes - simple payment processing
    Route::get('/payment/checkout/{booking}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{payment}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/invoice/{payment}', [PaymentController::class, 'invoice'])
        ->name('payment.invoice');
    Route::post('/payment/initiate', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
});

Route::post('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/fail', [PaymentController::class, 'fail'])->name('payment.fail');
Route::post('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/payment/ipn', [PaymentController::class, 'ipn'])->name('payment.ipn');

// Admin Auth
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->middleware('throttle:login');

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
    Route::put('/agencies/{agency}/approve', [AdminAgencyController::class, 'approve'])->name('admin.agencies.approve');
    Route::put('/agencies/{agency}/reject', [AdminAgencyController::class, 'reject'])->name('admin.agencies.reject');
    // Routes for viewing and downloading agency business documents
    Route::get('/agencies/{agency}/document/view', [AdminAgencyController::class, 'viewDocument'])->name('admin.agencies.document.view');
    Route::get('/agencies/{agency}/document/download', [AdminAgencyController::class, 'downloadDocument'])->name('admin.agencies.document.download');
    Route::delete('/agencies/{agency}', [AdminAgencyController::class, 'destroy'])->name('admin.agencies.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/export', [AdminUserController::class, 'export'])->name('admin.users.export');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('admin.bookings.show');
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
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('admin.payments.show');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
    Route::get('/home-content', [AdminHomeContentController::class, 'edit'])->name('admin.home-content.edit');
    Route::put('/home-content', [AdminHomeContentController::class, 'update'])->name('admin.home-content.update');
    Route::get('/page-heroes', [AdminPageHeroController::class, 'index'])->name('admin.page-heroes.index');
    Route::get('/page-heroes/{pageHero}/edit', [AdminPageHeroController::class, 'edit'])->name('admin.page-heroes.edit');
    Route::put('/page-heroes/{pageHero}', [AdminPageHeroController::class, 'update'])->name('admin.page-heroes.update');
    Route::resource('/experiences', AdminExperienceController::class)->names('admin.experiences');
    Route::resource('/stories', AdminStoryController::class)->names('admin.stories');
    Route::resource('/hotels', AdminHotelController::class)->names('admin.hotels');
    Route::resource('/transport', AdminTransportController::class)->names('admin.transport');
    Route::get('/{page}', [AdminController::class, 'placeholder'])->name('admin.placeholder');
});
