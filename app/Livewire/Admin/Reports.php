<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Reports extends Component
{
    public $summary = [];
    public $topProducts = [];
    public $chartLabels = [];
    public $chartValues = [];

    public function mount()
    {
        $this->loadSummary();
        $this->loadChartData();
        $this->loadTopProducts();
    }

    public function loadSummary()
    {
        $now = Carbon::now();

        // 1. Omzet Hari Ini
        $todayRevenue = Order::where('status', 'paid')
            ->whereDate('created_at', $now->today())
            ->sum('total_price');

        // 2. Omzet Bulan Ini
        $monthRevenue = Order::where('status', 'paid')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_price');

        // 3. Total Pesanan (Paid) Bulan Ini
        $totalOrders = Order::where('status', 'paid')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $this->summary = [
            'today' => $todayRevenue,
            'month' => $monthRevenue,
            'orders' => $totalOrders
        ];
    }

    public function loadChartData()
    {
        // Ambil data 7 hari terakhir
        $startDate = Carbon::now()->subDays(6);

        $data = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total')
        )
            ->where('status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format data untuk Chart.js
        $labels = [];
        $values = [];

        // Loop 7 hari terakhir untuk memastikan tanggal kosong tetap ada (nilai 0)
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $record = $data->firstWhere('date', $date);

            $labels[] = Carbon::parse($date)->isoFormat('dddd, D MMM'); // Contoh: Senin, 25 Jan
            $values[] = $record ? $record->total : 0;
        }

        $this->chartLabels = $labels;
        $this->chartValues = $values;
    }

    public function loadTopProducts()
    {
        // Cari 5 produk dengan quantity terjual terbanyak (status paid)
        $this->topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('order', function ($q) {
                $q->where('status', 'paid');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product') // Eager load relasi produk
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.reports')
            ->layout('components.admin-layout', ['header' => 'Laporan & Analitik']);
    }
}
