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

    // Variabel untuk Rincian Harga (Pajak & Service)
    public $subtotal = 0;
    public $tax = 0;          // PPN 11%
    public $service = 0;      // Service Charge 5%
    public $grandTotal = 0;   // Total Akhir yang harus dibayar

    // Variabel Modal QRIS
    public $isQrisModalOpen = false;

    protected $rules = [
        'paymentAmount' => 'required|numeric|min:0',
    ];

    /**
     * Buka Modal Detail & Hitung Pajak Otomatis
     */
    public function openDetail($orderId)
    {
        $this->selectedOrder = Order::with(['items.product', 'table'])->find($orderId);

        if ($this->selectedOrder) {
            // 1. Hitung Subtotal (Harga Menu x Jumlah)
            $this->subtotal = $this->selectedOrder->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            // 2. Hitung Service Charge 5% (Opsional)
            $this->service = ceil($this->subtotal * 0.05);

            // 3. Hitung PPN 11% (Dari Subtotal + Service)
            $this->tax = ceil(($this->subtotal + $this->service) * 0.11);

            // 4. Hitung Grand Total (Total Akhir)
            $this->grandTotal = $this->subtotal + $this->service + $this->tax;

            // Reset input pembayaran
            $this->paymentAmount = 0;
            $this->changeAmount = 0;
            $this->isQrisModalOpen = false; // Pastikan QRIS tertutup saat buka baru
        }
    }

    /**
     * Tutup Modal & Reset Data
     */
    public function closeDetail()
    {
        $this->selectedOrder = null;
        $this->reset(['paymentAmount', 'changeAmount', 'subtotal', 'tax', 'service', 'grandTotal', 'isQrisModalOpen']);
    }

    /**
     * Hitung Kembalian Real-time saat mengetik nominal pembayaran
     */
    public function updatedPaymentAmount()
    {
        // Hitung kembalian berdasarkan Grand Total
        if (is_numeric($this->paymentAmount)) {
            $this->changeAmount = (int)$this->paymentAmount - (int)$this->grandTotal;
        }
    }

    /**
     * Proses Pembayaran Tunai (Tandai Lunas & Potong Stok)
     */
    public function markAsPaid()
    {
        if (!$this->selectedOrder) return;

        $this->validate();

        // Validasi jika uang kurang
        if ($this->paymentAmount < $this->grandTotal) {
            $this->addError('paymentAmount', 'Uang pembayaran kurang!');
            return;
        }

        // Update Data Pesanan
        $this->selectedOrder->update([
            'status' => 'paid',
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax,
            'service_charge' => $this->service,
            'total_price' => $this->grandTotal, // Simpan total yang sudah kena pajak
            'updated_at' => now(),
        ]);

        // POTONG STOK OTOMATIS
        // (Memanggil fungsi reduceStock yang ada di Model Order)
        $this->selectedOrder->reduceStock();

        session()->flash('success', 'Pembayaran berhasil! Stok telah dikurangi.');

        // Jangan close detail agar kasir bisa langsung cetak struk
    }

    /**
     * --- LOGIKA QRIS PRIBADI ---
     */

    // Buka Modal QRIS
    public function openQris()
    {
        if ($this->selectedOrder) {
            $this->isQrisModalOpen = true;
        }
    }

    // Tutup Modal QRIS
    public function closeQris()
    {
        $this->isQrisModalOpen = false;
    }

    // Konfirmasi Pembayaran QRIS (Manual Check)
    public function markAsQrisPaid()
    {
        if ($this->selectedOrder) {
            // Set nominal bayar pas sesuai tagihan (karena transfer pasti pas)
            $this->paymentAmount = $this->grandTotal;

            // Panggil fungsi bayar utama
            $this->markAsPaid();

            // Tutup modal QRIS
            $this->closeQris();
        }
    }

    public function render()
    {
        // Tampilkan pesanan yang statusnya 'served' (siap bayar) atau 'paid' (riwayat hari ini)
        $orders = Order::with('table')
            ->whereIn('status', ['served', 'paid', 'completed'])
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10);

        return view('livewire.admin.cashier', [
            'orders' => $orders
        ])->layout('components.admin-layout', ['header' => 'Halaman Kasir (POS)']);
    }
}
