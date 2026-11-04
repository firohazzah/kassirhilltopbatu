<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerOrderController;

// ==========================================
// CUSTOMER (TANPA LOGIN)
// ==========================================

// Customer Kiosk
Route::get('/order', [CustomerOrderController::class, 'index'])
    ->name('customer.order');

Route::post('/order/checkout', [CustomerOrderController::class, 'checkout'])
    ->name('customer.checkout');

Route::get('/order/receipt/{transaction}', [CustomerOrderController::class, 'receipt'])
    ->middleware('signed')
    ->name('customer.receipt');

    Route::get('/order/status/{transaction}', [
    CustomerOrderController::class,
    'status'
])
->middleware('signed')
->name('customer.status');

// ==========================================
// LOGIN
// ==========================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


// ==========================================
// ADMIN & KASIR (WAJIB LOGIN)
// ==========================================

Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/kasir', [CashierController::class, 'create'])
        ->name('kasir.create');

    Route::post('/kasir', [CashierController::class, 'store'])
        ->name('kasir.store');

        
    Route::get('/transaksi', [CashierController::class, 'index'])
        ->name('transaksi.index');

    Route::get('/transaksi/{transaction}', [CashierController::class, 'show'])
        ->name('transaksi.show');

        // PESANAN KIOSK
Route::get('/pesanan-kiosk', [CashierController::class, 'kioskOrders'])
    ->name('kasir.kiosk');

Route::get('/pesanan-kiosk/{transaction}', [CashierController::class, 'kioskDetail'])
    ->name('kasir.kiosk.detail');

Route::post('/pesanan-kiosk/{transaction}/konfirmasi', [CashierController::class, 'confirmKioskPayment'])
    ->name('kasir.kiosk.confirm');


    // ==========================================
    // KHUSUS ADMIN
    // ==========================================

    Route::middleware(['admin'])->group(function () {

        Route::resource('produk', ProductController::class)
            ->except(['show']);

        Route::get('/produk/{produk}', [ProductController::class, 'show'])
            ->name('produk.show');

        Route::get('/laporan', [ReportController::class, 'index'])
            ->name('laporan.index');

        Route::get('/laporan/export', [ReportController::class, 'export'])
            ->name('laporan.export');
    });
});