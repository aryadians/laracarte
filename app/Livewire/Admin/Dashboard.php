<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        // 1. Hitung Pendapatan Hari Ini
        $todayRevenue = Order::where('status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price');

        // 2. Hitung Total Pesanan Hari Ini
        $todayOrders = Order::whereDate('created_at', Carbon::today())
            ->count();

        // 3. Hitung Meja yang Sedang Terisi (Status 'filled')
        // Asumsi: Kita perlu update status meja di OrderList saat checkout/selesai.
        // Untuk simpelnya, kita hitung order yang statusnya BELUM 'paid' (masih proses)
        $activeOrders = Order::where('status', '!=', 'paid')->count();

        // 4. Ambil 5 Pesanan Terbaru
        $recentOrders = Order::with('table')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'todayRevenue' => $todayRevenue,
            'todayOrders' => $todayOrders,
            'activeOrders' => $activeOrders,
            'recentOrders' => $recentOrders,
        ])->layout('components.admin-layout', ['header' => 'Dashboard']);
    }
}
