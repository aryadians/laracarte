<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Carbon\Carbon;

class TransactionHistory extends Component
{
    use WithPagination;

    public $dateFilter; // Untuk filter tanggal

    public function mount()
    {
        // Default tanggal hari ini
        $this->dateFilter = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        // Query Pesanan yang statusnya 'paid' (Lunas)
        $orders = Order::with('table')
            ->where('status', 'paid')
            ->whereDate('created_at', $this->dateFilter)
            ->latest()
            ->paginate(10);

        // Hitung Total Pendapatan Hari Ini (Sesuai Filter)
        $totalRevenue = Order::where('status', 'paid')
            ->whereDate('created_at', $this->dateFilter)
            ->sum('total_price');

        // Hitung Total Transaksi Hari Ini
        $totalTransactions = Order::where('status', 'paid')
            ->whereDate('created_at', $this->dateFilter)
            ->count();

        return view('livewire.admin.transaction-history', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions
        ])->layout('components.admin-layout', ['header' => 'Laporan Transaksi']);
    }
}
