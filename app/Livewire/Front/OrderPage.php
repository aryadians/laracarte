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

    public function mount($slug)
    {
        $this->table = Table::where('slug', $slug)->firstOrFail();
        $this->table_name = $this->table->name;
        session()->put('table_id', $this->table->id);
    }

    public function setCategory($id)
    {
        $this->activeCategory = $id;
    }

    // --- CART ---
    public function addToCart($productId)
    {
        $product = Product::find($productId);

        // Validasi stok
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

    public function getTotalPrice()
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

    // --- CHECKOUT ---
    // FIX: Nama method disamakan dengan view menjadi 'openCheckout'
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

    // --- SUBMIT ---
    public function submitOrder()
    {
        $this->validate([
            'customerName' => 'required|string|min:3|max:50',
        ], [
            'customerName.required' => 'Nama wajib diisi ya!',
            'customerName.min' => 'Nama terlalu pendek.',
        ]);

        // Gunakan Try-Catch untuk menangkap Error Database
        try {
            DB::transaction(function () {
                // 1. Buat Order
                $order = Order::create([
                    'table_id' => $this->table->id,
                    'customer_name' => $this->customerName,
                    'note' => $this->orderNote, // Pastikan kolom ini ada di DB orders
                    'total_price' => $this->getTotalPrice(),
                    'status' => 'pending'
                ]);

                // 2. Simpan Item
                foreach ($this->cart as $productId => $qty) {
                    $product = Product::lockForUpdate()->find($productId);

                    if ($product && $product->stock >= $qty) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $qty,
                            'price' => $product->price
                        ]);
                        $product->decrement('stock', $qty);
                    }
                }
            });

            // Jika Berhasil:
            $this->cart = [];
            $this->isCheckoutOpen = false;
            $this->customerName = '';
            $this->orderNote = '';
            session()->flash('success', true);
        } catch (\Exception $e) {
            // JIKA ERROR: Tampilkan pesan errornya di layar agar tahu masalahnya
            $this->addError('checkout_error', 'Gagal: ' . $e->getMessage());
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
