<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MidtransWebhookController;
use App\Livewire\Admin\ProductManager;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\OrderManager;
use App\Livewire\Admin\OrderHistory;
use App\Livewire\Admin\TransactionHistory;
use App\Livewire\Admin\TableManager;
use App\Livewire\Admin\Cashier;
use App\Livewire\Admin\Reports;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\IngredientManager;
use App\Livewire\Front\OrderIndex;
use App\Livewire\Front\OrderPage;
use App\Livewire\Admin\PrintReceipt;
use App\Livewire\Admin\Settings;

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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Manual Logout (Safety)
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Admin Panel Group
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/products', ProductManager::class)->name('products');
        Route::get('/ingredients', IngredientManager::class)->name('ingredients');
        Route::get('/kitchen', OrderManager::class)->name('kitchen');
        Route::get('/tables', TableManager::class)->name('tables');
        Route::get('/cashier', Cashier::class)->name('cashier');
        Route::get('/history', OrderHistory::class)->name('history');
        Route::get('/transactions', TransactionHistory::class)->name('transactions');
        Route::get('/reports', Reports::class)->name('reports');
        Route::get('/settings', Settings::class)->name('settings');
        
        // Route untuk Print Struk
        Route::get('/print/{orderId}', PrintReceipt::class)->name('print');
    });
});