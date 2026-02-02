<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'customer_name' => fake()->name(),
            'total_price' => fake()->numberBetween(10000, 500000),
            'status' => 'pending',
            'note' => fake()->sentence(),
        ];
    }
}
