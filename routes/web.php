<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Frontend Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Auth\SocialiteController; // tambahan ini untuk integrasi SSO Google
use App\Http\Controllers\ReviewController; // tambahan ini untuk integrasi fitur review & rating acara
use App\Http\Controllers\UserController; // TAMBAHAN: untuk integrasi Multi-Tenant & Kelola Pengguna


/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\TransactionController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama Publik
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk Kirim Ulasan & Rating Acara
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

// --- TAMBAHAN MULTI-TENANT: Route Upgrade Akun Jadi Organizer ---
Route::post('/upgrade-organizer', [UserController::class, 'upgradeToOrganizer'])->name('user.upgrade')->middleware('auth');

// Halaman Statis & Informasi
Route::get('/tentang', function () { return '<h1>Halaman Tentang</h1>'; })->name('about');
Route::get('/kontak',  function () { return view('contact'); })->name('contact');
Route::get('/bantuan', function () { return view('bantuan'); })->name('help');
Route::get('/cara-bayar', function () { return view('cara-bayar'); })->name('payment.info');

// User Features
Route::get('/profil',  function () { return view('profil'); })->name('profile');
Route::get('/katalog', function () { return view('katalog'); })->name('catalog');
Route::get('/ticket',  function () { return view('ticket'); })->name('ticket');

// Logout User Biasa
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Detail Event & Checkout
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

// Validasi Kupon via AJAX
Route::post('/api/coupons/validate', [\App\Http\Controllers\Api\CouponController::class, 'validateCoupon'])->name('api.coupons.validate');
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/e-ticket/{order_id}', [CheckoutController::class, 'ticket'])->name('checkout.ticket');

// --- INTEGRASI WEBHOOK MIDTRANS ---
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle'])->name('midtrans.callback');

// --- INTEGRASI SSO GOOGLE ---
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

// Redirect halaman bawaan /login ke halaman login admin
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

/*
|--------------------------------------------------------------------------
| Admin Routes Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    
    // [GUEST ONLY] Area Login Admin
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.post');
        Route::post('register', [AuthController::class, 'register'])->name('register.post');
    });

    // [PROTECTED] Area Dalam Admin Panel (Sudah Login)
    Route::middleware(['admin'])->group(function () {
        
        // =========================================================================
        // 1. ZONA UMUM (Bisa diakses oleh: ORGANIZER & SUPERADMIN)
        // =========================================================================
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', EventAdminController::class);
        Route::resource('events.tiers', \App\Http\Controllers\Admin\TicketTierController::class);
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
        
        // Fitur Check-in Scanner
        Route::get('/scanner', [\App\Http\Controllers\Admin\CheckinController::class, 'index'])->name('scanner');
        Route::post('/checkin', [\App\Http\Controllers\Admin\CheckinController::class, 'process'])->name('checkin');

        // Logout Admin
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');


        // =========================================================================
        // 2. ZONA DEWA (HANYA boleh diakses oleh: SUPERADMIN)
        // =========================================================================
        Route::middleware(['admin:superadmin'])->group(function () {
            
            // Semua rute sensitif di bawah ini resmi TERGEMBOK dari Organizer biasa:
            Route::resource('categories', CategoryController::class);
            Route::resource('partners', PartnerController::class);
            Route::resource('teams', \App\Http\Controllers\Admin\TeamController::class);
            Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
            
            // Rute Kelola Pengguna Platform
            Route::resource('users', UserController::class)->except(['show']);
            Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
            
        });
    });
});