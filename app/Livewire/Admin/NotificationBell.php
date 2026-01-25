<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;

class NotificationBell extends Component
{
    // Hapus wire:poll global jika ingin hemat resource, 
    // tapi biarkan jika ingin real-time update.

    public function markAsRead()
    {
        // Simpan waktu sekarang sebagai "terakhir dilihat"
        session()->put('last_notification_check', now());
    }

    public function render()
    {
        // 1. Ambil Waktu Terakhir Cek (Default: 100 tahun lalu jika belum pernah cek)
        $lastChecked = session('last_notification_check', Carbon::now()->subYears(100));

        // 2. Hitung Pesanan Pending yang BARU (Dibuat setelah terakhir cek)
        // Inilah yang membuat badge merah hilang setelah diklik
        $unreadCount = Order::where('status', 'pending')
            ->where('created_at', '>', $lastChecked)
            ->count();

        // 3. List Dropdown tetap menampilkan SEMUA yang pending (agar tidak hilang dari list)
        $notifications = Order::where('status', 'pending')
            ->with('table')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.admin.notification-bell', [
            'notifications' => $notifications,
            'count' => $unreadCount
        ]);
    }
}
