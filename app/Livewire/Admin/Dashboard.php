<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Table;
use App\Models\WaitressCall; // Pastikan Model ini di-import
use Carbon\Carbon;

use App\Models\Product; // Import Model Product
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function mount()
    {
        // Redirect Super Admin to their actual dashboard unless they are impersonating
        if (auth()->user()->hasRole(\App\Enums\UserRole::SUPER_ADMIN) && !session()->has('impersonator_id')) {
            return $this->redirectRoute('super-admin.dashboard', navigate: true);
        }
    }

    public function render()
    {
        // 1. Pendapatan Hari Ini
        $todayRevenue = Order::where('status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price');

        // 2. Pesanan Aktif (Dapur)
        $activeOrders = Order::whereIn('status', ['pending', 'cooking'])->count();

        // 3. Stok Menipis (Alert)
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->count();

        // 4. Produk Terlaris Hari Ini
        $topProduct = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('order', function($q) {
                $q->whereDate('created_at', Carbon::today())
                  ->where('status', 'paid');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->first();

        // 5. Panggilan Pelayan
        $waitressCalls = WaitressCall::with('table')
            ->where('status', 'pending')
            ->latest()
            ->get();

        // 6. Recent Orders
        $recentOrders = Order::with('table')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'todayRevenue' => $todayRevenue,
            'activeOrders' => $activeOrders,
            'lowStockCount' => $lowStockProducts,
            'topProduct' => $topProduct,
            'waitressCalls' => $waitressCalls,
            'recentOrders' => $recentOrders,
        ])->layout('components.admin-layout', ['header' => 'Dashboard']);
    }
}
