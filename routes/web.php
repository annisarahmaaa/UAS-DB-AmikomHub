<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransWebhookController; // Impor controller webhook baru

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

// Halaman Statis & Informasi
Route::get('/tentang', function () { return '<h1>Halaman Tentang</h1>'; })->name('about');
Route::get('/kontak',  function () { return view('contact'); })->name('contact');
Route::get('/bantuan', function () { return view('bantuan'); })->name('help');

// User Features
Route::get('/profil',  function () { return view('profil'); })->name('profile');
Route::get('/katalog', function () { return view('katalog'); })->name('catalog');
Route::get('/ticket',  function () { return view('ticket'); })->name('ticket');

// Detail Event & Checkout (Midtrans Integrasi)
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

// --- INTEGRASI WEBHOOK MIDTRANS CALLBACK ---
// Ditaruh di rute publik karena ditembak langsung oleh server Midtrans secara POST
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle'])->name('midtrans.callback');

// Redirect halaman bawaan /login ke halaman login admin resmi
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

/*
|--------------------------------------------------------------------------
| Admin Routes Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    
    // [GUEST ONLY] Hanya bisa diakses sebelum admin login
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.post');
    });

    // [PROTECTED] Hanya bisa diakses setelah login & lolos filter AdminMiddleware
    Route::middleware(['admin'])->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Master Data & CRUD (Resources)
        Route::resource('events', EventAdminController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);

        // Transactions Management
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // Proses Keluar Aplikasi
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});