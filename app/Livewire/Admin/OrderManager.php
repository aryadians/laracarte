<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;

class OrderManager extends Component
{
    // Agar halaman otomatis refresh setiap 10 detik untuk cek order baru
    protected $listeners = ['echo:orders,OrderCreated' => '$refresh'];

    // Update status jadi SEDANG DIMASAK (Opsional, jika ingin ada proses ini)
    public function markAsCooking($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status == 'pending') {
            $order->update(['status' => 'cooking']);
            session()->flash('message', 'Pesanan Meja ' . $order->table->name . ' sedang dimasak.');
        }
    }

    // FIX: Tombol "Siap Saji" / "Selesai Masak"
    // Mengubah status menjadi 'served' (Disajikan), AGAR KASIR BISA BAYAR
    public function markAsServed($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            // Ubah ke 'served' (Artinya makanan sudah diantar ke meja)
            // Kasir akan melihat ini sebagai pesanan yang belum dibayar
            $order->update(['status' => 'served']);

            session()->flash('message', 'Pesanan Meja ' . $order->table->name . ' siap disajikan!');
        }
    }

    // (Opsional) Jika Admin Dapur juga bisa terima pembayaran
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
        // Ambil pesanan Aktif (Pending, Cooking, Served)
        // Hilangkan yang sudah 'paid' atau 'completed' agar dapur bersih
        $orders = Order::with(['table', 'items.product'])
            ->whereIn('status', ['pending', 'cooking', 'served'])
            ->orderBy('created_at', 'asc') // Urutkan dari yang paling lama antri (FIFO)
            ->get();

        return view('livewire.admin.order-manager', [
            'orders' => $orders
        ])->layout('components.admin-layout', ['header' => 'Kitchen & Orders']);
    }
}
