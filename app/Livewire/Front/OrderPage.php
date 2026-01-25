<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\Table;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class OrderPage extends Component
{
    public $table;
    public $table_name; // To display in the header

    // UI & Filter State
    public $activeCategory = 'all';
    public $isCheckoutOpen = false;

    // Order Data
    public $cart = [];
    public $customerName = '';
    public $orderNote = '';

    /**
     * Initialize the component, find the table by its slug,
     * and set the table ID in the session.
     */
    public function mount($slug)
    {
        $this->table = Table::where('slug', $slug)->firstOrFail();
        $this->table_name = $this->table->name;

        session()->put('table_id', $this->table->id);
    }

    // --- CATEGORY FILTER ---
    public function setCategory($id)
    {
        $this->activeCategory = $id;
    }

    // --- CART FEATURES ---
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product || $product->stock <= ($this->cart[$productId] ?? 0)) {
            // Optional: Add a flash message for out of stock
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
        if (empty($this->cart)) {
            return 0;
        }
        $products = Product::whereIn('id', array_keys($this->cart))->get();
        foreach ($products as $product) {
            $total += $product->price * $this->cart[$product->id];
        }
        return $total;
    }

    // --- CHECKOUT MODAL ---
    public function openCheckout()
    {
        $this->isCheckoutOpen = true;
    }

    public function closeCheckout()
    {
        $this->isCheckoutOpen = false;
    }

    // --- ORDER SUBMISSION ---
    public function submitOrder()
    {
        $this->validate([
            'customerName' => 'required|min:3|max:50',
        ], [
            'customerName.required' => 'Nama pemesan wajib diisi.',
            'customerName.min' => 'Nama pemesan minimal 3 karakter.',
            'customerName.max' => 'Nama pemesan maksimal 50 karakter.',
        ]);

        // 1. Create the main order
        $order = Order::create([
            'table_id' => $this->table->id,
            'customer_name' => $this->customerName,
            'note' => $this->orderNote,
            'total_price' => $this->getTotalPrice(),
            'status' => 'pending' // Initial status
        ]);

        // 2. Save ordered items
        $productsToUpdateStock = [];
        foreach ($this->cart as $productId => $qty) {
            $product = Product::find($productId);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $product->price
            ]);
            
            // Prepare data for stock reduction
            $productsToUpdateStock[$productId] = $qty;
        }

        // Optional: Reduce stock in a batch
        foreach ($productsToUpdateStock as $id => $qty) {
            Product::find($id)->decrement('stock', $qty);
        }

        // 3. Reset state & notify success
        $this->cart = [];
        $this->isCheckoutOpen = false;
        $this->customerName = '';
        $this->orderNote = '';

        session()->flash('success', true); // Trigger success popup
    }

    /**
     * Dummy function to simulate calling a waitress.
     */
    public function callWaitress()
    {
        session()->flash('info', '✅ Siap! Pelayan akan segera datang ke meja Anda.');
    }

    // --- RENDER METHOD ---
    public function render()
    {
        // Get all categories for the top navigation
        $categories = Category::all();

        // Get products based on the active category filter
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
