<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Table;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LoyaltyProgramTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $owner;
    protected $table;
    protected $category;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tenant = Tenant::create(['name' => 'Test Restaurant', 'slug' => 'test-resto']);
        $this->owner = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => \App\Enums\UserRole::OWNER
        ]);
        
        $this->table = Table::create(['tenant_id' => $this->tenant->id, 'name' => 'Table 1', 'slug' => 'table-1']);
        $this->category = Category::create(['tenant_id' => $this->tenant->id, 'name' => 'Food', 'slug' => 'food']);
        $this->product = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Test Burger',
            'price' => 50000,
            'stock' => 10,
            'is_available' => true
        ]);

        // Default settings
        Setting::updateOrCreate(['tenant_id' => $this->tenant->id, 'key' => 'loyalty_enabled'], ['value' => '1']);
        Setting::updateOrCreate(['tenant_id' => $this->tenant->id, 'key' => 'point_earn_rate'], ['value' => '10000']);
        Setting::updateOrCreate(['tenant_id' => $this->tenant->id, 'key' => 'point_redeem_value'], ['value' => '100']);
    }

    public function test_customer_earns_points_on_order()
    {
        Livewire::test(\App\Livewire\Front\OrderPage::class, ['slug' => 'table-1'])
            ->set('cart', [
                'item-1' => [
                    'product_id' => $this->product->id,
                    'name' => $this->product->name,
                    'price' => 50000,
                    'qty' => 1,
                    'variants' => []
                ]
            ])
            ->set('customerName', 'John Doe')
            ->set('memberPhone', '081234567890')
            ->call('submitOrder');

        $customer = Customer::where('phone_number', '081234567890')->first();
        $this->assertNotNull($customer);
        
        // 50000 / 10000 = 5 points
        // But wait, there is tax and service charge.
        // Tax 11%, Service 5%
        // Subtotal: 50000
        // Service: 2500
        // Tax: (50000 + 2500) * 11% = 5775
        // Total: 58275
        // Points: 58275 / 10000 = 5 points
        $this->assertEquals(5, $customer->points_balance);
        $this->assertDatabaseHas('point_transactions', [
            'customer_id' => $customer->id,
            'type' => 'earn',
            'points' => 5
        ]);
    }

    public function test_customer_can_redeem_points_for_discount()
    {
        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Loyal John',
            'phone_number' => '081234567890',
            'points_balance' => 100
        ]);

        // 100 points * 100 Rp = 10.000 discount
        Livewire::test(\App\Livewire\Front\OrderPage::class, ['slug' => 'table-1'])
            ->set('memberPhone', '081234567890')
            ->set('cart', [
                'item-1' => [
                    'product_id' => $this->product->id,
                    'name' => $this->product->name,
                    'price' => 100000,
                    'qty' => 1,
                    'variants' => []
                ]
            ])
            ->set('pointsToRedeem', 100)
            ->set('customerName', 'Loyal John')
            ->call('submitOrder');

        $customer->refresh();
        // Points should be (100 - 100) + points from new order
        // New order total: 
        // Subtotal: 100000
        // Point Discount: 10000
        // Base for tax: 90000
        // Service: 4500
        // Tax: (90000 + 4500) * 11% = 10395
        // Total: 104895
        // Earn Points: 104895 / 10000 = 10 points
        $this->assertEquals(10, $customer->points_balance);
        
        $order = Order::latest()->first();
        $this->assertEquals(104895, $order->total_price);
        $this->assertStringContainsString('Point Redeem', $order->promo_name);
    }

    public function test_cannot_redeem_more_points_than_balance()
    {
        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Poor John',
            'phone_number' => '081234567890',
            'points_balance' => 10
        ]);

        Livewire::test(\App\Livewire\Front\OrderPage::class, ['slug' => 'table-1'])
            ->set('cart', [
                'item-1' => [
                    'product_id' => $this->product->id,
                    'name' => $this->product->name,
                    'price' => 50000,
                    'qty' => 1,
                    'variants' => []
                ]
            ])
            ->set('memberPhone', '081234567890')
            ->set('pointsToRedeem', 50)
            ->assertSet('pointsToRedeem', 10); // Automatically clamped to balance
    }
}
