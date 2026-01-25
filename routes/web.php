<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Livewire\Admin\ProductManager; // Import Livewire Component
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\OrderManager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Route Dashboard
// Route Dashboard (GANTI BARIS INI)
Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Group Auth Middleware (Hanya bisa diakses jika sudah login)
Route::middleware('auth')->group(function () {

    // 1. FIX ERROR PROFILE
    // Error "Route [profile] not defined" terjadi karena layout mencari route bernama 'profile'.
    // Jadi, kita ubah nama 'profile.edit' menjadi 'profile'.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2. RUTE PRODUK MANAGER (Livewire)
    Route::get('/products', ProductManager::class)->name('products');

    // 3. FIX ERROR LOGOUT
    // Mendefinisikan manual rute logout untuk mencegah error jika file auth.php bermasalah
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    // ... route produk manager ...
    Route::get('/orders', \App\Livewire\Admin\OrderList::class)->name('orders');
    Route::get('/tables', \App\Livewire\Admin\TableManager::class)->name('tables');
    Route::get('/history', \App\Livewire\Admin\OrderHistory::class)->name('history');
    Route::get('/admin/orders', OrderManager::class)->name('admin.orders');
});

// Memuat file auth bawaan (Login, Register, Reset Password, dll)
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
// Rute Publik untuk Pelanggan (Scan QR)
// URL contoh: http://laracarte.test/order/meja-1-xyz
Route::get('/order/{slug}', \App\Livewire\Front\OrderIndex::class)->name('order.index');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
