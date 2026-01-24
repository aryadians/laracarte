<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;

class OrderList extends Component
{
    // Update status pesanan
    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $status]);

        // Jika status 'paid' (selesai), mungkin kita mau hapus dari view ini atau tandai arsip
        if ($status == 'paid') {
            session()->flash('message', 'Pesanan Meja ' . $order->table->name . ' selesai & dibayar!');
        }
    }

    // Hapus pesanan (misal cancel/ghost order)
    public function deleteOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->items()->delete(); // Hapus item dulu
        $order->delete();
    }

    public function render()
    {
        // Ambil order yang statusnya BUKAN 'paid' (atau sesuaikan kebutuhan)
        // Kita urutkan dari yang terlama (Pending) biar diproses duluan
        $orders = Order::with(['table', 'items.product'])
            ->where('status', '!=', 'paid')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.admin.order-list', [
            'orders' => $orders
        ])->layout('components.admin-layout', ['header' => 'Pesanan Masuk (Dapur)']);
    }
}
