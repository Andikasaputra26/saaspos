<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StoreController;


Route::controller(AuthController::class)->group(function () {
    Route::get('/login',    'showLoginForm')->name('login');
    Route::post('/login',   'login')->name('login.post');

    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register','register')->name('register.post');

    Route::post('/logout',  'logout')->name('logout');
});

Route::get('/', fn() => view('welcome'))->name('welcome');

Route::middleware(['auth'])->group(function () {

    Route::prefix('stores')->name('stores.')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('index');
        Route::get('/create', [StoreController::class, 'create'])->name('create');
        Route::post('/', [StoreController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [StoreController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StoreController::class, 'update'])->name('update');
        Route::delete('/{id}', [StoreController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/select', [StoreController::class, 'select'])->name('select');
    });

    Route::middleware(['tenant'])->group(function () {

        Route::get('/dashboard',       [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/data', [DashboardController::class, 'getSalesData'])->name('dashboard.data');


        Route::resource('products', ProductController::class);
        Route::post('/products/{id}/activate', [ProductController::class, 'activate'])->name('products.activate');
        Route::post('/products/{id}/deactivate', [ProductController::class, 'deactivate'])
                ->name('products.deactivate');


        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [SalesController::class, 'index'])->name('index');
            Route::post('/store', [SalesController::class, 'store'])->name('store');
            Route::get('/invoice/{id}', [SalesController::class, 'invoice'])->name('invoice');
            Route::get('/history', [SalesController::class, 'history'])->name('history');
            Route::delete('/{id}', [SalesController::class, 'destroy'])->name('destroy');
        });


        Route::prefix('purchases')->name('purchases.')->group(function () {
            Route::get('/', [PurchaseController::class, 'index'])->name('index');
            Route::get('/{id}', [PurchaseController::class, 'show'])->name('show');
            Route::get('/api/list', [PurchaseController::class, 'getPurchases'])->name('get');
        });


        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/',              [ReportController::class, 'index'])->name('index');
            Route::get('/stock_movement', [ReportController::class, 'stockMovement'])->name('stock_movement');
        });
    });
});
