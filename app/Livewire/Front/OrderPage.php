<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\Table;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WaitressCall;
use Illuminate\Support\Facades\DB;

class OrderPage extends Component
{
    public $table;
    public $table_name;

    // UI & Filter State
    public $activeCategory = 'all';
    public $isCheckoutOpen = false;

    // Order Data
    public $cart = [];
    public $customerName = '';
    public $orderNote = '';

    // BARU: Pilihan Pembayaran (Default: Bayar di Kasir)
    public $paymentMethod = 'cashier';

    // Settings
    public $taxRate = 0;
    public $serviceRate = 0;

    public function mount($slug)
    {
        $this->table = Table::where('slug', $slug)->firstOrFail();
        $this->table_name = $this->table->name;
        session()->put('table_id', $this->table->id);

        $this->taxRate = (int) \App\Models\Setting::value('tax_rate', 11);
        $this->serviceRate = (int) \App\Models\Setting::value('service_charge', 5);
    }

    public function setCategory($id)
    {
        $this->activeCategory = $id;
    }

    // --- CART LOGIC ---
    public function addToCart($productId)
    {
        $product = Product::find($productId);

        // Validasi stok & ketersediaan
        if (!$product || !$product->is_available || $product->stock <= ($this->cart[$productId] ?? 0)) {
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]++;
        } else {
            $this->cart[$productId] = 1;
        }
    }

    public function removeFromCart($productId)
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId] > 1) {
                $this->cart[$productId]--;
            } else {
                unset($this->cart[$productId]);
            }
        }
    }

    public function getTotalItems()
    {
        return array_sum($this->cart);
    }

    public function getSubtotal()
    {
        $total = 0;
        if (empty($this->cart)) return 0;

        $products = Product::whereIn('id', array_keys($this->cart))->get();
        foreach ($products as $product) {
            if (isset($this->cart[$product->id])) {
                $total += $product->price * $this->cart[$product->id];
            }
        }
        return $total;
    }

    public function getTotalPrice()
    {
        return $this->getSubtotal();
    }

    public function getServiceCharge()
    {
        return ceil($this->getSubtotal() * ($this->serviceRate / 100));
    }

    public function getTaxAmount()
    {
        return ceil(($this->getSubtotal() + $this->getServiceCharge()) * ($this->taxRate / 100));
    }

    public function getGrandTotal()
    {
        return $this->getSubtotal() + $this->getServiceCharge() + $this->getTaxAmount();
    }

    // --- CHECKOUT UI ---
    public function openCheckout()
    {
        if (count($this->cart) > 0) {
            $this->isCheckoutOpen = true;
        }
    }

    public function closeCheckout()
    {
        $this->isCheckoutOpen = false;
    }

    // --- CALL WAITRESS ---
    public function callWaitress()
    {
        $existingCall = WaitressCall::where('table_id', $this->table->id)
            ->where('status', 'pending')
            ->first();

        if (!$existingCall) {
            WaitressCall::create([
                'table_id' => $this->table->id,
                'status' => 'pending'
            ]);
        }
        session()->flash('success_waitress', 'Pelayan sedang menuju ke mejamu!');
    }

    // --- SUBMIT ORDER (INTI PERUBAHAN) ---
    public function submitOrder()
    {
        $this->validate([
            'customerName' => 'required|string|min:3|max:50',
            'paymentMethod' => 'required|in:cashier,qris', // Validasi Metode Pembayaran
        ], [
            'customerName.required' => 'Nama wajib diisi ya!',
            'customerName.min' => 'Nama terlalu pendek.',
        ]);

        if (empty($this->cart)) return;

        try {
            DB::transaction(function () {
                $subtotal = $this->getSubtotal();
                $service = $this->getServiceCharge();
                $tax = $this->getTaxAmount();
                $grandTotal = $this->getGrandTotal();

                // 1. Buat Order
                $order = Order::create([
                    'table_id' => $this->table->id,
                    'customer_name' => $this->customerName,
                    'note' => $this->orderNote,
                    'subtotal' => $subtotal,
                    'service_charge' => $service,
                    'tax_amount' => $tax,
                    'total_price' => $grandTotal,
                    'status' => 'pending',
                    'payment_method' => $this->paymentMethod, // Simpan Pilihan User
                    'stock_reduced' => true, // KITA KURANGI STOK DISINI (Agar tidak double di kasir)
                ]);

                // 2. Simpan Item & Kurangi Stok
                foreach ($this->cart as $productId => $qty) {
                    // Lock for update agar stok aman saat transaksi bersamaan
                    $product = Product::lockForUpdate()->find($productId);

                    if ($product) {
                        // Cek stok lagi biar aman
                        if ($product->stock < $qty) {
                            throw new \Exception("Stok {$product->name} habis/kurang!");
                        }

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $qty,
                            'price' => $product->price
                        ]);

                        // Kurangi stok sekarang (Reservasi stok)
                        $product->decrement('stock', $qty);
                    }
                }
            });

            // 3. Reset Cart & UI
            $this->cart = [];
            $this->isCheckoutOpen = false;
            $this->customerName = '';
            $this->orderNote = '';
            $this->paymentMethod = 'cashier'; // Reset ke default

            // 4. Kirim Pesan Sukses (Beda pesan tergantung metode)
            if ($this->paymentMethod == 'qris') {
                session()->flash('success', 'Pesanan masuk! Mohon tunjukkan bukti transfer ke kasir.');
            } else {
                session()->flash('success', 'Pesanan berhasil! Silakan bayar di kasir setelah makan.');
            }
        } catch (\Exception $e) {
            // Tampilkan error ke user
            $this->addError('checkout_error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categories = Category::all();
        $products = Product::query()
            ->where('is_available', true)
            ->when($this->activeCategory !== 'all', function ($q) {
                $q->where('category_id', $this->activeCategory);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.front.order-page', [
            'categories' => $categories,
            'products' => $products
        ])->layout('layouts.shop');
    }
}
