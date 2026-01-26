<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Table;
use App\Models\WaitressCall; // Pastikan Model ini di-import
use Carbon\Carbon;

class Dashboard extends Component
{
    /**
     * Fungsi untuk menandai bahwa panggilan pelayan sudah selesai/didatangi.
     * Dipanggil saat tombol "Sudah Didatangi" diklik di dashboard.
     */
    public function markAsSolved($callId)
    {
        $call = WaitressCall::find($callId);
        if ($call) {
            $call->update(['status' => 'solved']);
        }
    }

    public function render()
    {
        // 1. Hitung Pendapatan Hari Ini (Hanya yang status 'paid')
        $todayRevenue = Order::where('status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price');

        // 2. Hitung Total Pesanan Hari Ini
        $todayOrders = Order::whereDate('created_at', Carbon::today())
            ->count();

        // 3. Hitung Pesanan Aktif (Status belum 'paid' atau 'completed')
        // Ini merepresentasikan meja yang sedang makan/menunggu
        $activeOrders = Order::whereNotIn('status', ['paid', 'completed'])->count();

        // 4. Ambil 5 Pesanan Terbaru untuk Tabel
        $recentOrders = Order::with('table')
            ->latest()
            ->take(5)
            ->get();

        // 5. FITUR BARU: Ambil Panggilan Waitress yang statusnya 'pending'
        // Data ini akan memicu alert merah dan suara notifikasi di view
        $waitressCalls = WaitressCall::with('table')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('livewire.admin.dashboard', [
            'todayRevenue' => $todayRevenue,
            'todayOrders' => $todayOrders,
            'activeOrders' => $activeOrders,
            'recentOrders' => $recentOrders,
            'waitressCalls' => $waitressCalls, // Kirim data panggilan ke view
        ])->layout('components.admin-layout', ['header' => 'Dashboard']);
    }
}
