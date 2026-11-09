<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

// ==========================================
// ROUTE AUTH
// ==========================================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// ROUTE ADMIN
// ==========================================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class)->except(['show']);
});

// ==========================================
// ROUTE KASIR (admin & kasir bisa akses)
// ==========================================
Route::middleware(['auth', 'kasir'])->group(function () {
    Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir.index');
    Route::post('/kasir', [TransactionController::class, 'store'])->name('kasir.store');
    Route::get('/kasir/struk/{id}', [TransactionController::class, 'struk'])->name('kasir.struk');
});
