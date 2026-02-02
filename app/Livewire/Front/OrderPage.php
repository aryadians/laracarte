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
use App\Models\Customer;
use App\Models\PointTransaction;
use App\Models\Promo;
use App\Events\OrderCreated;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderPage extends Component
{
    public $table;
    public $table_name;
    public $taxRate;
    public $serviceRate;

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

    // Loyalty Program
    public $memberPhone = '';
    public $memberPoints = 0;
    public $isMember = false;
    public $pointsToRedeem = 0;
    public $pointRedeemValue = 0;
    public $pointEarnRate = 10000;
    public $loyaltyEnabled = true;

    // Promo
    public $discountAmount = 0;
    public $appliedPromoName = null;

    public function mount($slug)
    {
        $this->table = Table::where('slug', $slug)->firstOrFail();
        $this->table_name = $this->table->name;
        session()->put('table_id', $this->table->id);

        $this->taxRate = (int) \App\Models\Setting::value('tax_rate', 11);
        $this->serviceRate = (int) \App\Models\Setting::value('service_charge', 5);

        $this->loyaltyEnabled = (bool) \App\Models\Setting::value('loyalty_enabled', 1);
        $this->pointEarnRate = (int) \App\Models\Setting::value('point_earn_rate', 10000);
        $this->pointRedeemValue = (int) \App\Models\Setting::value('point_redeem_value', 100);
    }

    public function setCategory($id)
    {
        $this->activeCategory = $id;
    }

    // --- MEMBER LOGIC ---
    public function updatedMemberPhone()
    {
        if (strlen($this->memberPhone) >= 10) {
            $customer = Customer::where('phone_number', $this->memberPhone)->first();
            if ($customer) {
                $this->isMember = true;
                $this->memberPoints = $customer->points_balance;
            } else {
                $this->isMember = false;
                $this->memberPoints = 0;
                $this->pointsToRedeem = 0;
            }
        } else {
            $this->isMember = false;
            $this->pointsToRedeem = 0;
        }
    }

    public function updatedPointsToRedeem()
    {
        if ($this->pointsToRedeem > $this->memberPoints) {
            $this->pointsToRedeem = $this->memberPoints;
        }

        if ($this->pointsToRedeem < 0) {
            $this->pointsToRedeem = 0;
        }
        
        // Maksimal diskon poin tidak boleh melebihi subtotal
        $pointDiscount = $this->pointsToRedeem * $this->pointRedeemValue;
        if ($pointDiscount > $this->getSubtotal()) {
            $this->pointsToRedeem = floor($this->getSubtotal() / $this->pointRedeemValue);
        }
    }

    // --- CART LOGIC ---
    
    public function addToCart($productId)
    {
        $product = Product::with(['variants.options'])->find($productId);
        if (!$product || !$product->is_available || $product->stock <= 0) return;

        if ($product->variants->isNotEmpty()) {
            $this->openVariantModal($product);
        } else {
            $this->directAddToCart($product);
        }
    }

    public function openVariantModal($product)
    {
        $this->selectedProduct = $product;
        $this->selectedVariants = [];
        $this->currentPrice = $product->price;
        $this->isVariantModalOpen = true;
    }

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

    public function saveVariantToCart()
    {
        foreach ($this->selectedProduct->variants as $variant) {
            if ($variant->is_required) {
                if (!isset($this->selectedVariants[$variant->id]) || empty($this->selectedVariants[$variant->id])) {
                    $this->addError('variant_error', "Wajib memilih " . $variant->name);
                    return;
                }
                if ($variant->type == 'checkbox') {
                    $hasSelection = collect($this->selectedVariants[$variant->id])->contains(fn($val) => $val == true);
                    if (!$hasSelection) {
                        $this->addError('variant_error', "Wajib memilih " . $variant->name);
                        return;
                    }
                }
            }
        }

        $savedVariants = [];
        foreach ($this->selectedProduct->variants as $variant) {
            if (isset($this->selectedVariants[$variant->id])) {
                $selection = $this->selectedVariants[$variant->id];
                if ($variant->type == 'radio') {
                    $option = $variant->options->find($selection);
                    if ($option) {
                        $savedVariants[] = ['variant_name' => $variant->name, 'option_name' => $option->name, 'option_id' => $option->id, 'price' => $option->price];
                    }
                } elseif ($variant->type == 'checkbox') {
                    foreach ($selection as $optId => $isSelected) {
                        if ($isSelected) {
                            $option = $variant->options->find($optId);
                            if ($option) {
                                $savedVariants[] = ['variant_name' => $variant->name, 'option_name' => $option->name, 'option_id' => $option->id, 'price' => $option->price];
                            }
                        }
                    }
                }
            }
        }

        $uuid = (string) Str::uuid();
        $this->cart[$uuid] = [
            'product_id' => $this->selectedProduct->id,
            'name' => $this->selectedProduct->name,
            'price' => $this->currentPrice,
            'variants' => $savedVariants,
            'qty' => 1
        ];

        $this->isVariantModalOpen = false;
        $this->reset(['selectedProduct', 'selectedVariants', 'currentPrice']);
    }

    public function directAddToCart($product)
    {
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
            $this->cart[$uuid] = ['product_id' => $product->id, 'name' => $product->name, 'price' => $product->price, 'variants' => [], 'qty' => 1];
        }
    }

    public function incrementQty($uuid)
    {
        if (isset($this->cart[$uuid])) {
            $product = Product::find($this->cart[$uuid]['product_id']);
            $totalQtyInCart = collect($this->cart)->where('product_id', $product->id)->sum('qty');
            if ($product->stock > $totalQtyInCart) $this->cart[$uuid]['qty']++;
        }
    }

    public function decrementQty($uuid)
    {
        if (isset($this->cart[$uuid])) {
            if ($this->cart[$uuid]['qty'] > 1) $this->cart[$uuid]['qty']--;
            else unset($this->cart[$uuid]);
        }
    }

    public function getTotalItems() { return collect($this->cart)->sum('qty'); }
    public function getSubtotal() { return collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']); }

    public function getDiscountAmount()
    {
        $subtotal = $this->getSubtotal();
        $bestDiscount = 0;
        $bestPromoName = null;

        $promos = Promo::where('is_active', true)
            ->where('min_purchase', '<=', $subtotal)
            ->get();

        foreach ($promos as $promo) {
            $currentDiscount = ($promo->type == 'percentage') ? $subtotal * ($promo->value / 100) : $promo->value;
            if ($currentDiscount > $bestDiscount) {
                $bestDiscount = $currentDiscount;
                $bestPromoName = $promo->name;
            }
        }

        $this->discountAmount = $bestDiscount;
        $this->appliedPromoName = $bestPromoName;
        return $bestDiscount;
    }

    public function getPointDiscount()
    {
        return $this->pointsToRedeem * $this->pointRedeemValue;
    }

    public function getServiceCharge() { 
        $base = $this->getSubtotal() - $this->getDiscountAmount() - $this->getPointDiscount();
        return ceil(max(0, $base) * ($this->serviceRate / 100)); 
    }

    public function getTaxAmount() { 
        $base = ($this->getSubtotal() - $this->discountAmount - $this->getPointDiscount()) + $this->getServiceCharge();
        return ceil(max(0, $base) * ($this->taxRate / 100)); 
    }

    public function getGrandTotal() { 
        $total = ($this->getSubtotal() - $this->discountAmount - $this->getPointDiscount()) + $this->getServiceCharge() + $this->getTaxAmount(); 
        return max(0, $total);
    }

    public function openCheckout() { if (!empty($this->cart)) $this->isCheckoutOpen = true; }
    public function closeCheckout() { $this->isCheckoutOpen = false; }

    public function callWaitress()
    {
        $existingCall = WaitressCall::where('table_id', $this->table->id)->where('status', 'pending')->first();
        if (!$existingCall) WaitressCall::create(['table_id' => $this->table->id, 'status' => 'pending']);
        session()->flash('success_waitress', 'Pelayan sedang menuju ke mejamu!');
    }

    public function submitOrder()
    {
        $this->validate([
            'customerName' => 'required|string|min:3|max:50',
            'paymentMethod' => 'required|in:cashier,midtrans',
            'memberPhone' => 'nullable|numeric|digits_between:10,15',
        ]);

        if (empty($this->cart)) return;

        try {
            $snapToken = null;

            DB::transaction(function () use (&$snapToken) {
                // 1. Handle Member
                $customerId = null;
                if ($this->memberPhone) {
                    $customer = Customer::firstOrCreate(
                        ['phone_number' => $this->memberPhone],
                        ['name' => $this->customerName]
                    );
                    $customer->update(['last_visit' => now(), 'name' => $this->customerName]);
                    $customerId = $customer->id;
                }

                // 2. Buat Order
                $order = Order::create([
                    'table_id' => $this->table->id,
                    'customer_name' => $this->customerName,
                    'note' => $this->orderNote . ($this->memberPhone ? " (Member: {$this->memberPhone})" : ""),
                    'subtotal' => $this->getSubtotal(),
                    'discount_amount' => $this->discountAmount + $this->getPointDiscount(),
                    'promo_name' => $this->appliedPromoName . ($this->pointsToRedeem > 0 ? " + Point Redeem" : ""),
                    'service_charge' => $this->getServiceCharge(),
                    'tax_amount' => $this->getTaxAmount(),
                    'total_price' => $this->getGrandTotal(),
                    'status' => 'pending',
                    'payment_method' => $this->paymentMethod,
                    'stock_reduced' => true,
                ]);

                // 3. Simpan Item & Varian
                foreach ($this->cart as $uuid => $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);
                    if ($product) {
                        $orderItem = OrderItem::create(['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => $item['qty'], 'price' => $item['price']]);
                        foreach ($item['variants'] as $variant) {
                            OrderItemVariant::create(['order_item_id' => $orderItem->id, 'product_variant_option_id' => $variant['option_id'], 'variant_name' => $variant['variant_name'], 'option_name' => $variant['option_name'], 'price' => $variant['price']]);
                        }
                        $product->decrement('stock', $item['qty']);
                    }
                }

                // 4. Hitung Poin & Potong Poin Redeem
                if ($customerId) {
                    // Potong poin jika ada redeem
                    if ($this->pointsToRedeem > 0) {
                        PointTransaction::create([
                            'customer_id' => $customerId,
                            'order_id' => $order->id,
                            'type' => 'redeem',
                            'points' => $this->pointsToRedeem,
                            'description' => 'Redeem poin untuk Order #' . $order->id
                        ]);
                        Customer::where('id', $customerId)->decrement('points_balance', $this->pointsToRedeem);
                    }

                    // Tambah poin dari belanja (berdasarkan grand total setelah dikurangi poin)
                    $pointsEarned = floor($order->total_price / $this->pointEarnRate);
                    if ($pointsEarned > 0) {
                        PointTransaction::create([
                            'customer_id' => $customerId,
                            'order_id' => $order->id,
                            'type' => 'earn',
                            'points' => $pointsEarned,
                            'description' => 'Reward Order #' . $order->id
                        ]);
                        Customer::where('id', $customerId)->increment('points_balance', $pointsEarned);
                    }
                }

                if ($this->paymentMethod == 'midtrans') {
                    $midtrans = new MidtransService();
                    $snapToken = $midtrans->getSnapToken($order);
                    $order->update(['payment_token' => $snapToken]);
                }

                OrderCreated::dispatch($order);
            });

            if ($this->paymentMethod == 'midtrans') {
                $this->dispatch('pay-with-midtrans', token: $snapToken);
            } else {
                $this->cart = [];
                $this->isCheckoutOpen = false;
                $this->customerName = '';
                $this->orderNote = '';
                $this->memberPhone = '';
                $this->pointsToRedeem = 0;
                session()->flash('success', 'Pesanan berhasil! Poin telah ditambahkan.');
            }
        } catch (\Exception $e) {
            $this->addError('checkout_error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categories = Category::all();
        $products = Product::query()->with(['variants'])->where('is_available', true)->when($this->activeCategory !== 'all', function ($q) { $q->where('category_id', $this->activeCategory); })->orderBy('name')->get();
        return view('livewire.front.order-page', ['categories' => $categories, 'products' => $products])->layout('layouts.shop');
    }
}