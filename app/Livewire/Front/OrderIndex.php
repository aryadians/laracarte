<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;

class OrderIndex extends Component
{
    // Data Meja
    public $table_slug;
    public $table_name;

    // Data Filter & Cart
    public $activeCategory = 'all';
    public $cart = []; // Format: [product_id => quantity]

    // Data Checkout
    public $isCheckoutOpen = false;
    public $customerName = '';
    public $orderNote = ''; // Variabel untuk Catatan Pesanan

    public function mount($slug)
    {
        // 1. Cek apakah Meja Valid berdasarkan URL
        $table = Table::where('slug', $slug)->firstOrFail();
        $this->table_slug = $table->slug;
        $this->table_name = $table->name;
    }

    // --- FITUR FILTER KATEGORI ---
    public function setCategory($id)
    {
        $this->activeCategory = $id;
    }

    // --- FITUR KERANJANG BELANJA ---
    public function addToCart($productId)
    {
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

    public function getTotalPrice()
    {
        $total = 0;
        // Ambil data produk yang ada di cart saja untuk menghitung harga
        $products = Product::whereIn('id', array_keys($this->cart))->get();

        foreach ($products as $product) {
            $total += $product->price * $this->cart[$product->id];
        }

        return $total;
    }

    public function getTotalItems()
    {
        return array_sum($this->cart);
    }

    // --- FITUR CHECKOUT & SUBMIT ---

    // Buka Modal
    public function checkout()
    {
        // Cegah checkout jika keranjang kosong
        if (empty($this->cart)) {
            return;
        }
        $this->isCheckoutOpen = true;
    }

    // Tutup Modal
    public function closeCheckout()
    {
        $this->isCheckoutOpen = false;
    }

    // Proses Simpan ke Database
    public function submitOrder()
    {
        // 1. Validasi Input
        $this->validate([
            'customerName' => 'required|min:3|max:50',
            'cart' => 'required|array|min:1',
            'orderNote' => 'nullable|string|max:255', // Validasi untuk catatan
        ], [
            'customerName.required' => 'Nama pemesan wajib diisi ya!',
            'customerName.min' => 'Nama terlalu pendek.',
        ]);

        // 2. Ambil Data Meja Terkini
        $table = Table::where('slug', $this->table_slug)->first();

        // 3. Simpan Data Order Utama
        $order = Order::create([
            'table_id' => $table->id,
            'customer_name' => $this->customerName,
            'total_price' => $this->getTotalPrice(),
            'status' => 'pending',      // Status awal: Menunggu
            'payment_method' => 'cash', // Default metode bayar
            'note' => $this->orderNote, // <-- FIX: Simpan catatan dari input user
        ]);

        // 4. Simpan Rincian Item (Looping Cart)
        $products = Product::whereIn('id', array_keys($this->cart))->get();

        foreach ($products as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $this->cart[$product->id],
                'unit_price' => $product->price,
                'subtotal' => $product->price * $this->cart[$product->id],
            ]);
        }

        // 5. Update Status Meja jadi "Terisi"
        $table->update(['status' => 'filled']);

        // 6. Reset Form (Termasuk Note) & Tampilkan Notifikasi Sukses
        $this->reset(['cart', 'customerName', 'orderNote', 'isCheckoutOpen']);
        session()->flash('success', 'Pesanan berhasil dikirim! Mohon tunggu sebentar.');
    }

    public function render()
    {
        // Filter Produk Berdasarkan Kategori
        $query = Product::query()->where('is_available', true);

        if ($this->activeCategory !== 'all') {
            $query->where('category_id', $this->activeCategory);
        }

        return view('livewire.front.order-index', [
            'categories' => Category::where('is_active', true)->get(),
            'products' => $query->get(),
        ])->layout('layouts.customer');
    }
}
