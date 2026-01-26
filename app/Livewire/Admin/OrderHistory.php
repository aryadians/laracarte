<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Illuminate\Support\Facades\Response;

class OrderHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $date = ''; // Filter Tanggal

    // Reset pagination saat melakukan pencarian
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query Dasar: Hanya yang statusnya 'paid'
        $query = Order::with(['items.product', 'table'])
            ->where('status', 'paid');

        // Filter Pencarian (Nama Customer / ID Order)
        // PENTING: Menggunakan closure function($q) agar logika 'orWhere' tidak merusak filter 'paid'
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('id', $this->search);
            });
        }

        // Filter Tanggal
        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        // Urutkan dari yang terbaru
        $orders = $query->latest()->paginate(10);

        return view('livewire.admin.order-history', [
            'orders' => $orders
        ])->layout('components.admin-layout', ['header' => 'Riwayat Transaksi']);
    }

    // --- FITUR EXPORT CSV ---
    public function exportCsv()
    {
        // 1. Ambil data dengan filter yang sama persis dengan render()
        $query = Order::with('table')->where('status', 'paid');

        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('id', $this->search);
            });
        }

        $orders = $query->latest()->get();

        // 2. Nama File
        $fileName = 'Laporan_Transaksi_' . date('Y-m-d_H-i') . '.csv';

        // 3. Download Stream
        return response()->streamDownload(function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Header Kolom
            fputcsv($file, ['ID Order', 'Waktu', 'Pelanggan', 'Meja', 'Total Bayar', 'Status']);

            // Isi Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    '#' . $order->id,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->customer_name,
                    $order->table->name ?? 'Takeaway',
                    $order->total_price, // Format angka mentah agar bisa dihitung Excel
                    'LUNAS'
                ]);
            }

            fclose($file);
        }, $fileName);
    }

    // Reset Semua Filter
    public function resetFilters()
    {
        $this->reset(['search', 'date']);
        $this->resetPage();
    }
}
