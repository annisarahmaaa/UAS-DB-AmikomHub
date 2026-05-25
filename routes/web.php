<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama Publik (Mengirimkan variabel events, categories, dan partners)
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Halaman Statis & Informasi
Route::get('/tentang', function () { return '<h1>Halaman Tentang</h1>'; })->name('about');
Route::get('/kontak',  function () { return view('contact'); })->name('contact');
Route::get('/bantuan', function () { return view('bantuan'); })->name('help');

// User Features
Route::get('/profil',       function () { return view('profil'); })->name('profile');
Route::get('/katalog',      function () { return view('katalog'); })->name('catalog');
Route::get('/detail-event', function () { return view('event-detail'); })->name('event.detail');
Route::get('/checkout',     function () { return view('checkout'); })->name('checkout');
Route::get('/ticket',       function () { return view('ticket'); })->name('ticket');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Menggunakan grouping prefix 'admin' dan penamaan rute 'admin.'
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Events (Resource: Mengelola CRUD secara otomatis)
    Route::resource('events', EventAdminController::class);

    // Categories (Diubah menjadi Resource untuk memenuhi kelengkapan CRUD Soal 1)
    Route::resource('categories', CategoryController::class);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    // Partners (Diubah menjadi Resource untuk memenuhi kelengkapan CRUD Soal 2)
    Route::resource('partners', PartnerController::class);

});