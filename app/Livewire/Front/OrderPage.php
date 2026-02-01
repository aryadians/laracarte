<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\Table;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemVariant;
use App\Models\WaitressCall;
use App\Events\OrderCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderPage extends Component
{
    public $table;
    public $table_name;

    // UI & Filter State
    public $activeCategory = 'all';
    public $isCheckoutOpen = false;
    
    // Modal Varian
    public $isVariantModalOpen = false;
    public $selectedProduct = null;
    public $selectedVariants = []; // [variant_id => option_id] atau [variant_id => [option_id, ...]]
    public $currentPrice = 0;

    // Order Data
    public $cart = []; // Struktur: [ uuid => { product_id, qty, price, variants: [] } ]
    public $customerName = '';
    public $orderNote = '';
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
    
    // 1. Klik Tombol Tambah
    public function addToCart($productId)
    {
        $product = Product::with(['variants.options'])->find($productId);

        if (!$product || !$product->is_available || $product->stock <= 0) return;

        // Cek apakah produk punya varian?
        if ($product->variants->isNotEmpty()) {
            $this->openVariantModal($product);
        } else {
            // Jika tidak ada varian, langsung masuk cart
            $this->directAddToCart($product);
        }
    }

    // 2. Buka Modal Varian
    public function openVariantModal($product)
    {
        $this->selectedProduct = $product;
        $this->selectedVariants = [];
        $this->currentPrice = $product->price;
        
        // Auto-select opsi pertama untuk varian radio (optional, tapi bagus untuk UX)
        foreach ($product->variants as $variant) {
            if ($variant->type == 'radio' && $variant->options->isNotEmpty()) {
                // $this->selectedVariants[$variant->id] = $variant->options->first()->id;
            }
        }
        
        $this->calculateVariantPrice();
        $this->isVariantModalOpen = true;
    }

    // Hitung harga saat pilih opsi
    public function updatedSelectedVariants()
    {
        $this->calculateVariantPrice();
    }

    public function calculateVariantPrice()
    {
        if (!$this->selectedProduct) return;
        
        $basePrice = $this->selectedProduct->price;
        $addonPrice = 0;

        foreach ($this->selectedProduct->variants as $variant) {
            if (isset($this->selectedVariants[$variant->id])) {
                $selection = $this->selectedVariants[$variant->id];

                if ($variant->type == 'radio') {
                    $option = $variant->options->find($selection);
                    if ($option) $addonPrice += $option->price;
                } 
                elseif ($variant->type == 'checkbox' && is_array($selection)) {
                    foreach ($selection as $optId => $isSelected) {
                        if ($isSelected) {
                            $option = $variant->options->find($optId);
                            if ($option) $addonPrice += $option->price;
                        }
                    }
                }
            }
        }

        $this->currentPrice = $basePrice + $addonPrice;
    }

    // 3. Simpan Item Varian ke Cart
    public function saveVariantToCart()
    {
        // Validasi Required Variants
        foreach ($this->selectedProduct->variants as $variant) {
            if ($variant->is_required) {
                if (!isset($this->selectedVariants[$variant->id]) || empty($this->selectedVariants[$variant->id])) {
                    $this->addError('variant_error', "Wajib memilih " . $variant->name);
                    return;
                }
                
                // Khusus checkbox, pastikan ada yang true
                if ($variant->type == 'checkbox') {
                    $hasSelection = collect($this->selectedVariants[$variant->id])->contains(fn($val) => $val == true);
                    if (!$hasSelection) {
                        $this->addError('variant_error', "Wajib memilih " . $variant->name);
                        return;
                    }
                }
            }
        }

        // Susun Data Varian untuk Disimpan
        $savedVariants = [];
        foreach ($this->selectedProduct->variants as $variant) {
            if (isset($this->selectedVariants[$variant->id])) {
                $selection = $this->selectedVariants[$variant->id];
                
                if ($variant->type == 'radio') {
                    $option = $variant->options->find($selection);
                    if ($option) {
                        $savedVariants[] = [
                            'variant_name' => $variant->name,
                            'option_name' => $option->name,
                            'option_id' => $option->id,
                            'price' => $option->price
                        ];
                    }
                } elseif ($variant->type == 'checkbox') {
                    foreach ($selection as $optId => $isSelected) {
                        if ($isSelected) {
                            $option = $variant->options->find($optId);
                            if ($option) {
                                $savedVariants[] = [
                                    'variant_name' => $variant->name,
                                    'option_name' => $option->name,
                                    'option_id' => $option->id,
                                    'price' => $option->price
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Masukkan ke Cart
        $cartItem = [
            'product_id' => $this->selectedProduct->id,
            'name' => $this->selectedProduct->name,
            'price' => $this->currentPrice, // Harga Satuan Total (Base + Varian)
            'variants' => $savedVariants,
            'qty' => 1
        ];

        // Generate UUID unik untuk setiap kombinasi varian
        // Tapi sederhananya: selalu buat item baru kalau ada varian
        $uuid = (string) Str::uuid();
        $this->cart[$uuid] = $cartItem;

        $this->isVariantModalOpen = false;
        $this->reset(['selectedProduct', 'selectedVariants', 'currentPrice']);
    }

    // Helper: Add Tanpa Varian
    public function directAddToCart($product)
    {
        // Cek apakah item polos ini sudah ada di cart?
        $existingKey = null;
        foreach ($this->cart as $key => $item) {
            if ($item['product_id'] == $product->id && empty($item['variants'])) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey) {
            $this->cart[$existingKey]['qty']++;
        } else {
            $uuid = (string) Str::uuid();
            $this->cart[$uuid] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'variants' => [],
                'qty' => 1
            ];
        }
    }

    // --- CART ACTIONS ---
    public function incrementQty($uuid)
    {
        if (isset($this->cart[$uuid])) {
            $product = Product::find($this->cart[$uuid]['product_id']);
            
            // Validasi Stok Global Produk
            // Hitung total qty produk ini di seluruh cart (karena bisa beda varian)
            $totalQtyInCart = 0;
            foreach ($this->cart as $item) {
                if ($item['product_id'] == $product->id) {
                    $totalQtyInCart += $item['qty'];
                }
            }

            if ($product->stock > $totalQtyInCart) {
                $this->cart[$uuid]['qty']++;
            }
        }
    }

    public function decrementQty($uuid)
    {
        if (isset($this->cart[$uuid])) {
            if ($this->cart[$uuid]['qty'] > 1) {
                $this->cart[$uuid]['qty']--;
            } else {
                unset($this->cart[$uuid]);
            }
        }
    }

    public function getTotalItems()
    {
        return collect($this->cart)->sum('qty');
    }

    public function getSubtotal()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
    }

    // --- CHECKOUT & SUBMIT ---
    
    // (Fungsi getServiceCharge, getTaxAmount, getGrandTotal SAMA SEPERTI SEBELUMNYA)
    public function getServiceCharge() { return ceil($this->getSubtotal() * ($this->serviceRate / 100)); }
    public function getTaxAmount() { return ceil(($this->getSubtotal() + $this->getServiceCharge()) * ($this->taxRate / 100)); }
    public function getGrandTotal() { return $this->getSubtotal() + $this->getServiceCharge() + $this->getTaxAmount(); }

    public function openCheckout() { if (!empty($this->cart)) $this->isCheckoutOpen = true; }
    public function closeCheckout() { $this->isCheckoutOpen = false; }

    public function submitOrder()
    {
        $this->validate([
            'customerName' => 'required|string|min:3|max:50',
            'paymentMethod' => 'required|in:cashier,qris',
        ]);

        if (empty($this->cart)) return;

        try {
            DB::transaction(function () {
                // 1. Buat Order
                $order = Order::create([
                    'table_id' => $this->table->id,
                    'customer_name' => $this->customerName,
                    'note' => $this->orderNote,
                    'subtotal' => $this->getSubtotal(),
                    'service_charge' => $this->getServiceCharge(),
                    'tax_amount' => $this->getTaxAmount(),
                    'total_price' => $this->getGrandTotal(),
                    'status' => 'pending',
                    'payment_method' => $this->paymentMethod,
                    'stock_reduced' => true,
                ]);

                // 2. Simpan Item & Varian
                foreach ($this->cart as $uuid => $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    if ($product) {
                        if ($product->stock < $item['qty']) {
                            throw new \Exception("Stok {$product->name} habis/kurang!");
                        }

                        // Simpan Order Item
                        $orderItem = OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $item['qty'],
                            'price' => $item['price'] // Harga sudah termasuk varian
                        ]);

                        // Simpan Rincian Varian (Jika ada)
                        foreach ($item['variants'] as $variant) {
                            OrderItemVariant::create([
                                'order_item_id' => $orderItem->id,
                                'product_variant_option_id' => $variant['option_id'],
                                'variant_name' => $variant['variant_name'],
                                'option_name' => $variant['option_name'],
                                'price' => $variant['price']
                            ]);
                        }

                        // Kurangi stok
                        $product->decrement('stock', $item['qty']);
                    }
                }

                // 3. Broadcast
                OrderCreated::dispatch($order);
            });

            // 4. Reset
            $this->cart = [];
            $this->isCheckoutOpen = false;
            $this->customerName = '';
            $this->orderNote = '';
            
            session()->flash('success', 'Pesanan berhasil! Silakan bayar di kasir.');

        } catch (\Exception $e) {
            $this->addError('checkout_error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categories = Category::all();
        $products = Product::query()
            ->with(['variants']) // Eager load variants untuk cek di blade
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
