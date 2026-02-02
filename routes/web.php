<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MidtransWebhookController;
use App\Livewire\Admin\ProductManager;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Admin\OrderManager;
use App\Livewire\Admin\OrderHistory;
use App\Livewire\Admin\TransactionHistory;
use App\Livewire\Admin\TableManager;
use App\Livewire\Admin\Cashier;
use App\Livewire\Admin\Reports;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\IngredientManager;
use App\Livewire\Admin\Expo;
use App\Livewire\Admin\PromoManager;
use App\Livewire\Front\OrderIndex;
use App\Livewire\Front\OrderPage;
use App\Livewire\Front\Kiosk;
use App\Livewire\Admin\PrintReceipt;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Loyalty;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Webhook Midtrans (Public)
Route::post('/webhook/midtrans', [MidtransWebhookController::class, 'handle']);

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Customer Routes (Scan QR)
Route::get('/order/{slug}', OrderIndex::class)->name('order.index');
Route::get('/table/{slug}', OrderPage::class)->name('front.order');
Route::get('/kiosk/{slug}', Kiosk::class)->name('front.kiosk');

// Auth Routes (Login, Register, etc.)
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}

// Authenticated Routes
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', Dashboard::class)
        ->middleware(['verified'])
        ->name('dashboard');

    // Profile Management
    Route::view('/profile', 'profile')->name('profile');

    // Manual Logout (Safety)
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Employee Management Routes
    Volt::route('employees', 'admin.employees.index')
        ->middleware(['role:owner']) // Enforce Owner Role
        ->name('employees.index');

    Volt::route('employees/create', 'admin.employees.create')
        ->middleware(['role:owner']) // Enforce Owner Role
        ->name('employees.create');

    // Admin Panel Group
    // Super Admin Routes (Platform Management)
    Route::middleware(['role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
        Route::get('/dashboard', \App\Livewire\SuperAdmin\Dashboard::class)->name('dashboard');
        Route::get('/tenants', \App\Livewire\SuperAdmin\Tenants::class)->name('tenants');
        Route::get('/settings', \App\Livewire\SuperAdmin\Settings::class)->name('settings');
        Route::get('/financials', \App\Livewire\SuperAdmin\Financials::class)->name('financials');
    });

    Route::post('/impersonate/leave', [\App\Http\Controllers\ImpersonationController::class, 'leave'])->name('impersonate.leave');

    // Admin Panel Group (RBAC)
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Owner ONLY Routes (Master Data, Reports, Settings)
        Route::middleware(['role:owner'])->group(function () {
            Route::get('/products', ProductManager::class)->name('products');
            Route::get('/ingredients', IngredientManager::class)->name('ingredients');
            Route::get('/promos', PromoManager::class)->name('promos');
            Route::get('/reports', Reports::class)->name('reports');
            Route::get('/transactions', TransactionHistory::class)->name('transactions'); // Laporan Harian
            Route::get('/settings', Settings::class)->name('settings');
            Route::get('/loyalty', Loyalty::class)->name('loyalty');
        });

        // Kitchen Routes (Kitchen & Owner)
        Route::middleware(['role:kitchen,owner'])->group(function () {
            Route::get('/kitchen', OrderManager::class)->name('kitchen');
            Route::get('/expo', Expo::class)->name('expo'); // Expo/Runner can be Kitchen too
        });

        // Cashier Routes (Cashier & Owner)
        Route::middleware(['role:cashier,owner'])->group(function () {
            Route::get('/cashier', Cashier::class)->name('cashier');
            Route::get('/history', OrderHistory::class)->name('history'); // Riwayat Transaksi
            
            // Route untuk Print Struk
            Route::get('/print/{orderId}', PrintReceipt::class)->name('print');
        });

        // Table Management (Owner, Cashier, Waiter)
        Route::middleware(['role:owner,cashier,waiter'])->group(function () {
            Route::get('/tables', TableManager::class)->name('tables');
        });
    });
});