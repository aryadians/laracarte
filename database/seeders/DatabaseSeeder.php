<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin untuk Login
        User::create([
            'name' => 'Admin LaraCarte',
            'email' => 'admin@laracarte.com',
            'password' => bcrypt('password'), // Password: password
        ]);

        // 2. Buat Kategori Menu
        $coffee = Category::create([
            'name' => 'Coffee',
            'slug' => 'coffee',
            'icon' => '☕',
            'is_active' => true,
        ]);

        $nonCoffee = Category::create([
            'name' => 'Non-Coffee',
            'slug' => 'non-coffee',
            'icon' => '🥤',
            'is_active' => true,
        ]);

        $meals = Category::create([
            'name' => 'Makanan Berat',
            'slug' => 'meals',
            'icon' => '🍛',
            'is_active' => true,
        ]);

        $snacks = Category::create([
            'name' => 'Snacks',
            'slug' => 'snacks',
            'icon' => '🍟',
            'is_active' => true,
        ]);

        // 3. Buat Produk Dummy

        // Produk Coffee
        Product::create([
            'category_id' => $coffee->id,
            'name' => 'Kopi Susu Gula Aren',
            'description' => 'Espresso dengan susu segar dan gula aren asli.',
            'price' => 18000,
            'image' => null, // Nanti kita update fitur upload gambar
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $coffee->id,
            'name' => 'Americano',
            'description' => 'Espresso shot dengan air panas.',
            'price' => 15000,
            'image' => null,
            'is_available' => true,
        ]);

        // Produk Makanan
        Product::create([
            'category_id' => $meals->id,
            'name' => 'Nasi Goreng Spesial',
            'description' => 'Nasi goreng dengan telor mata sapi dan sate ayam.',
            'price' => 25000,
            'image' => null,
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $snacks->id,
            'name' => 'French Fries',
            'description' => 'Kentang goreng renyah dengan saus sambal.',
            'price' => 12000,
            'image' => null,
            'is_available' => true,
        ]);

        // 4. Buat Data Meja (QR Code Simulation)
        for ($i = 1; $i <= 5; $i++) {
            Table::create([
                'name' => 'Meja ' . $i,
                'slug' => Str::random(10), // Kode unik acak untuk QR
                'status' => 'empty',
            ]);
        }
    }
}
