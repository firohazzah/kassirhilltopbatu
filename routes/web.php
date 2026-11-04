<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ReportController;

// Rute Tamu (Sebelum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout (Hanya yang sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute yang mewajibkan Login
Route::middleware(['auth'])->group(function () {

    // ==========================================
    // 1. BISA DIAKSES OLEH ADMIN & KASIR (PENGGUNA)
    // ==========================================
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kasir', [CashierController::class, 'create'])->name('kasir.create');
    Route::post('/kasir', [CashierController::class, 'store'])->name('kasir.store');
    Route::get('/transaksi', [CashierController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{transaction}', [CashierController::class, 'show'])->name('transaksi.show');

    // ==========================================
    // 2. KHUSUS ADMIN SAJA (MENU PRODUK & LAPORAN)
    // ==========================================
    Route::middleware(['admin'])->group(function () {
        Route::resource('produk', ProductController::class)->except(['show']);
        Route::get('/produk/{produk}', [ProductController::class, 'show'])->name('produk.show');
        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [ReportController::class, 'export'])->name('laporan.export');
    });
});
