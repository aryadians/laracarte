<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;

class Expo extends Component
{
    // Agar update otomatis jika ada event broadcast dari Dapur
    protected $listeners = ['echo:kitchen,OrderCreated' => '$refresh']; 
    // Catatan: Idealnya ada event khusus 'OrderServed', tapi refresh global juga oke untuk MVP.

    public function markAsCompleted($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status == 'served') {
            $order->update(['status' => 'completed']);
            session()->flash('message', 'Pesanan Meja ' . ($order->table->name ?? '?') . ' selesai diantar.');
        }
    }

    public function render()
    {
        // Ambil pesanan yang statusnya 'served' (Siap Saji dari Dapur)
        $readyOrders = Order::with(['table', 'items.product'])
            ->where('status', 'served')
            ->orderBy('updated_at', 'asc') // FIFO: Yang pertama jadi, diantar duluan
            ->get();

        return view('livewire.admin.expo', [
            'orders' => $readyOrders
        ])->layout('components.admin-layout', ['header' => 'Expo (Siap Saji)']);
    }
}