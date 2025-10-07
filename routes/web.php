<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StoreController;

/*
|--------------------------------------------------------------------------
| Web Routes – Aplikasi POS Multi-Toko
|--------------------------------------------------------------------------
|
| Fitur utama:
| 🔹 Autentikasi (Login, Register, Logout)
| 🔹 Manajemen Toko (Store Multi-Tenant)
| 🔹 Produk per Toko
| 🔹 Penjualan (Kasir POS)
| 🔹 Pembelian (Stok Masuk)
| 🔹 Laporan & Analitik
|
*/

// ======================================================================
// 🔐 AUTHENTICATION
// ======================================================================
Route::controller(AuthController::class)->group(function () {
    Route::get('/login',    'showLoginForm')->name('login');
    Route::post('/login',   'login')->name('login.post');

    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register','register')->name('register.post');

    Route::post('/logout',  'logout')->name('logout');
});

// ======================================================================
// 🏠 LANDING PAGE
// ======================================================================
Route::get('/', fn() => view('welcome'))->name('welcome');

// ======================================================================
// 🧭 PROTECTED ROUTES (AUTH REQUIRED)
// ======================================================================
Route::middleware(['auth'])->group(function () {

    // ------------------------------------------------------------------
    // 🏪 STORE MANAGEMENT
    // ------------------------------------------------------------------
    Route::prefix('stores')->name('stores.')->group(function () {
        Route::get('/',            [StoreController::class, 'index'])->name('index');
        Route::get('/create',      [StoreController::class, 'create'])->name('create');
        Route::post('/',           [StoreController::class, 'store'])->name('store');
        Route::get('/edit/{id}',   [StoreController::class, 'edit'])->name('edit');
        Route::put('/{id}',        [StoreController::class, 'update'])->name('update');
        Route::delete('/{id}',     [StoreController::class, 'destroy'])->name('destroy');
        Route::get('/select/{id}', [StoreController::class, 'select'])->name('select');
    });

    // ------------------------------------------------------------------
    // 🏢 TENANT-SCOPED ROUTES (BERDASARKAN TOKO AKTIF)
    // ------------------------------------------------------------------
    Route::middleware(['tenant'])->group(function () {

        // ==============================================================
        // 📊 DASHBOARD
        // ==============================================================
        Route::get('/dashboard',       [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/data',  [DashboardController::class, 'getData'])->name('dashboard.data');

        // ==============================================================
        // 📦 PRODUCTS
        // ==============================================================
        Route::resource('products', ProductController::class);

        // ==============================================================
        // 💰 SALES (KASIR POS)
        // ==============================================================
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/',           [SalesController::class, 'index'])->name('index');
            Route::post('/store',     [SalesController::class, 'store'])->name('store');
            Route::get('/invoice/{id}', [SalesController::class, 'invoice'])->name('invoice');
            Route::get('/history',    [SalesController::class, 'history'])->name('history');
        });

        // ==============================================================
        // 📥 PURCHASES (STOK MASUK)
        // ==============================================================
        Route::prefix('purchases')->name('purchases.')->group(function () {
            Route::get('/',           [PurchaseController::class, 'index'])->name('index');
            Route::get('/create',     [PurchaseController::class, 'create'])->name('create');
            Route::post('/',          [PurchaseController::class, 'store'])->name('store');
            Route::get('/invoice/{id}', [PurchaseController::class, 'invoice'])->name('invoice');
        });

        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/',              [ReportController::class, 'index'])->name('index');
        });
    });
});
