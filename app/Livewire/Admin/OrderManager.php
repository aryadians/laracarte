<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;

class OrderManager extends Component
{
    // Agar halaman otomatis refresh setiap 10 detik untuk cek order baru
    // Kamu bisa ubah angkanya, misal wire:poll.5s (5 detik)

    public function markAsCompleted($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => 'completed']);
            session()->flash('message', 'Pesanan Meja ' . $order->table->name . ' selesai dimasak!');
        }
    }

    public function markAsPaid($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => 'paid']);
            session()->flash('message', 'Pesanan Meja ' . $order->table->name . ' sudah dibayar.');
        }
    }

    public function deleteOrder($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->delete();
        }
    }

    public function render()
    {
        // Ambil pesanan yang belum dibayar (pending & completed)
        // Urutkan dari yang terbaru
        $orders = Order::with(['table', 'items.product'])
            ->whereIn('status', ['pending', 'completed']) // Tampilkan yg belum lunas
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.order-manager', [
            'orders' => $orders
        ])->layout('components.admin-layout', ['header' => 'Kitchen & Orders']);
    }
}
