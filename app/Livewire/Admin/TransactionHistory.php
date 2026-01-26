<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class TransactionHistory extends Component
{
    use WithPagination;

    public $dateFilter; // Variabel tanggal khusus file ini

    public function mount()
    {
        // Default tanggal hari ini
        $this->dateFilter = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        // Query Dasar
        $query = Order::with('table')
            ->where('status', 'paid')
            ->whereDate('created_at', $this->dateFilter);

        // Hitung Statistik
        $totalRevenue = $query->sum('total_price');
        $totalTransactions = $query->count();

        // Ambil Data Pagination
        // Kita clone query agar hitungan di atas tidak rusak oleh paginate
        $orders = $query->clone()
            ->latest()
            ->paginate(10);

        return view('livewire.admin.transaction-history', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions
        ])->layout('components.admin-layout');
    }

    // --- LOGIKA EXPORT CSV (WAJIB ADA) ---
    public function exportCsv()
    {
        $orders = Order::with('table')
            ->where('status', 'paid')
            ->whereDate('created_at', $this->dateFilter)
            ->latest()
            ->get();

        $fileName = 'Laporan_Harian_' . $this->dateFilter . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Waktu', 'Pelanggan', 'Meja', 'Total', 'Status']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    '#' . $order->id,
                    $order->created_at->format('H:i'),
                    $order->customer_name,
                    $order->table->name ?? 'Takeaway',
                    $order->total_price,
                    'LUNAS'
                ]);
            }
            fclose($file);
        }, $fileName);
    }
}
