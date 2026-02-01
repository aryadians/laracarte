<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use Barryvdh\DomPDF\Facade\Pdf;

class Reports extends Component
{
    public $summary = [];
    public $topProducts = [];
    public $chartLabels = [];
    public $chartValues = [];
    
    // Filter Date Range
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        
        $this->loadSummary();
        $this->loadChartData();
        $this->loadTopProducts();
    }

    public function updatedStartDate() { $this->refreshData(); }
    public function updatedEndDate() { $this->refreshData(); }

    public function refreshData()
    {
        $this->loadSummary();
        $this->loadChartData();
        $this->loadTopProducts();
    }

    public function exportExcel()
    {
        return Excel::download(new OrdersExport($this->startDate, $this->endDate), 'Laporan_Penjualan_' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        $orders = Order::with(['items.product', 'table'])
            ->where('status', 'paid')
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->get();

        $pdf = Pdf::loadView('pdf.sales_report', [
            'orders' => $orders,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'totalRevenue' => $orders->sum('total_price')
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Penjualan_' . date('Y-m-d') . '.pdf');
    }

    public function loadSummary()
    {
        // 1. Omzet Total (Dalam Range)
        $totalRevenue = Order::where('status', 'paid')
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->sum('total_price');

        // 2. Total Pesanan (Dalam Range)
        $totalOrders = Order::where('status', 'paid')
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->count();
            
        // 3. Rata-rata per Order
        $avgOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $this->summary = [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'avg_order' => $avgOrder
        ];
    }

    public function loadChartData()
    {
        // Ambil data dalam range tanggal
        $data = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total')
        )
            ->where('status', 'paid')
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $this->chartLabels = $data->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
        $this->chartValues = $data->pluck('total')->toArray();
    }

    public function loadTopProducts()
    {
        $this->topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(quantity * price) as total_sales'))
            ->whereHas('order', function ($q) {
                $q->where('status', 'paid')
                  ->whereDate('created_at', '>=', $this->startDate)
                  ->whereDate('created_at', '<=', $this->endDate);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.reports')
            ->layout('components.admin-layout', ['header' => 'Laporan & Analitik']);
    }
}
