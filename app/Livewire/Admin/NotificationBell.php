<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;

class NotificationBell extends Component
{
    // Auto-refresh setiap 5 detik untuk cek order baru

    public function render()
    {
        // Ambil 5 pesanan terbaru yang statusnya 'pending'
        $unreadNotifications = Order::where('status', 'pending')
            ->with('table')
            ->latest()
            ->take(5)
            ->get();

        // Hitung total pending
        $unreadCount = Order::where('status', 'pending')->count();

        return view('livewire.admin.notification-bell', [
            'notifications' => $unreadNotifications,
            'count' => $unreadCount
        ]);
    }
}
