<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class Cashier extends Component
{
    use WithPagination;

    public $selectedOrder = null; // Menampung pesanan yang sedang diproses
    public $paymentAmount = 0;    // Input uang dari kasir
    public $changeAmount = 0;     // Kembalian

    // Buka Modal Detail / Bayar
    public function openDetail($orderId)
    {
        $this->selectedOrder = Order::with(['items.product', 'table'])->find($orderId);
        $this->paymentAmount = 0; 
        $this->changeAmount = 0;
    }

    // Tutup Modal
    public function closeDetail()
    {
        $this->selectedOrder = null;
    }

    // Hitung Kembalian Real-time saat mengetik
    public function updatedPaymentAmount()
    {
        if ($this->selectedOrder) {
            $pay = (int) $this->paymentAmount;
            $total = $this->selectedOrder->total_price;
            $this->changeAmount = $pay - $total;
        }
    }

    // Proses Pembayaran (Tandai Lunas)
    public function markAsPaid()
    {
        if (!$this->selectedOrder) return;

        // Validasi pembayaran kurang
        if ($this->paymentAmount < $this->selectedOrder->total_price) {
            $this->addError('paymentAmount', 'Uang pembayaran kurang!');
            return;
        }

        // Update Status jadi Paid
        $this->selectedOrder->update([
            'status' => 'paid',
            'updated_at' => now(),
        ]);

        session()->flash('success', 'Pembayaran berhasil! Silakan cetak struk.');
    }

    public function render()
    {
        // Ambil data pesanan, urutkan dari yang terbaru
        $orders = Order::with('table')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.cashier', [
            'orders' => $orders
        ])->layout('components.admin-layout'); // Sesuaikan dengan layout admin kamu
    }
}