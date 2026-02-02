<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateChildTenantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Order Items (from Orders)
        $this->command->info('Fixing Order Items...');
        $orders = DB::table('orders')->whereNotNull('tenant_id')->get();
        foreach ($orders as $order) {
            DB::table('order_items')
                ->where('order_id', $order->id)
                ->update(['tenant_id' => $order->tenant_id]);
        }

        // 2. Product Variants (from Products)
        $this->command->info('Fixing Product Variants...');
        $products = DB::table('products')->whereNotNull('tenant_id')->get();
        foreach ($products as $product) {
            DB::table('product_variants')
                ->where('product_id', $product->id)
                ->update(['tenant_id' => $product->tenant_id]);

            DB::table('product_ingredients')
                ->where('product_id', $product->id)
                ->update(['tenant_id' => $product->tenant_id]);
        }

        // 3. Product Variant Options (from Variants)
        $this->command->info('Fixing Product Variant Options...');
        $variants = DB::table('product_variants')->whereNotNull('tenant_id')->get();
        foreach ($variants as $variant) {
            DB::table('product_variant_options')
                ->where('product_variant_id', $variant->id)
                ->update(['tenant_id' => $variant->tenant_id]);
        }

        // 4. Order Item Variants (from Order Items)
        $this->command->info('Fixing Order Item Variants...');
        $orderItems = DB::table('order_items')->whereNotNull('tenant_id')->get();
        foreach ($orderItems as $item) {
            DB::table('order_item_variants')
                ->where('order_item_id', $item->id)
                ->update(['tenant_id' => $item->tenant_id]);
        }
    }
}
