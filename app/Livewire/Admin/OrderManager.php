<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;

class OrderManager extends Component
{
    // Agar halaman otomatis refresh jika ada event broadcast (Opsional)
    // Jika tidak pakai Pusher/Echo, pastikan di blade ada wire:poll
    protected $listeners = ['echo:orders,OrderCreated' => '$refresh'];

    /**
     * Update status: PENDING -> COOKING
     * Menandakan koki mulai memasak pesanan ini.
     */
    public function markAsCooking($orderId)
    {
        $order = Order::find($orderId);

        // Hanya bisa dimasak jika status awalnya 'pending'
        if ($order && $order->status == 'pending') {
            $order->update(['status' => 'cooking']);
            session()->flash('message', 'Pesanan Meja ' . ($order->table->name ?? 'Takeaway') . ' sedang dimasak.');
        }
    }

    /**
     * Update status: COOKING -> SERVED (SIAP SAJI)
     * Menandakan makanan sudah jadi dan siap diantar.
     * PENTING: Stok berkurang di tahap ini.
     */
    public function markAsServed($orderId)
    {
        $order = Order::find($orderId);

        if ($order) {
            // 1. Ubah status ke 'served'
            $order->update(['status' => 'served']);

            // 2. POTONG STOK OTOMATIS
            // Memanggil fungsi yang sudah kita buat di Model Order
            $order->reduceStock();

            session()->flash('message', 'Pesanan Meja ' . ($order->table->name ?? 'Takeaway') . ' siap disajikan & Stok dikurangi!');
        }
    }

    /**
     * (Opsional) Hapus Pesanan (Misal salah input / cancel)
     */
    public function deleteOrder($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            // Jika statusnya belum paid/completed, kembalikan stok (opsional, tergantung kebijakan)
            // Tapi untuk MVP, kita delete saja.
            $order->delete();
            session()->flash('message', 'Pesanan berhasil dihapus.');
        }
    }

    public function render()
    {
        // Ambil pesanan Aktif untuk tampilan Dapur
        // Kita hanya ambil status: Pending (Baru) & Cooking (Dimasak)
        // Status 'served' biasanya sudah hilang dari layar koki, tapi jika ingin tetap ditampilkan, biarkan array-nya.
        $orders = Order::with(['table', 'items.product'])
            ->whereIn('status', ['pending', 'cooking', 'served'])
            ->orderBy('created_at', 'asc') // Urutkan dari yang paling lama antri (FIFO)
            ->get();

        return view('livewire.admin.order-manager', [
            'orders' => $orders
        ])->layout('components.admin-layout', ['header' => 'Kitchen Display System (KDS)']);
    }
}
