<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Table;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT USER ADMIN
        User::create([
            'name' => 'Admin LaraCarte',
            'email' => 'admin@laracarte.com',
            'password' => bcrypt('password123'),
            'role' => 'admin', // Pastikan kolom role ada di tabel users
        ]);

        $this->command->info('✅ User Admin berhasil dibuat!');

        // 2. BUAT DATA MEJA (Agar link QR tidak Not Found)
        $tables = ['Meja 1', 'Meja 2', 'Meja 3', 'Meja 4', 'Meja 5', 'Meja VIP'];
        foreach ($tables as $tableName) {
            Table::create([
                'name' => $tableName,
                'slug' => Str::slug($tableName), // meja-1, meja-2, dst
                'status' => 'empty'
            ]);
        }
        $this->command->info('✅ 6 Meja berhasil dibuat!');

        // 3. BUAT KATEGORI
        $catMakanan = Category::create(['name' => 'Makanan Berat', 'slug' => 'makanan-berat']);
        $catMinuman = Category::create(['name' => 'Minuman', 'slug' => 'minuman']);
        $catSnack = Category::create(['name' => 'Cemilan', 'slug' => 'cemilan']);

        // 4. BUAT PRODUK/MENU DUMMY
        $products = [
            [
                'category_id' => $catMakanan->id,
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur, ayam suwir, dan kerupuk udang.',
                'price' => 25000,
                'image' => null, // Bisa diisi url gambar jika ada
                'is_available' => true,
            ],
            [
                'category_id' => $catMakanan->id,
                'name' => 'Ayam Bakar Madu',
                'description' => 'Ayam bakar dengan olesan madu murni + lalapan.',
                'price' => 30000,
                'image' => null,
                'is_available' => true,
            ],
            [
                'category_id' => $catMinuman->id,
                'name' => 'Es Teh Manis',
                'description' => 'Teh melati asli dengan gula batu.',
                'price' => 5000,
                'image' => null,
                'is_available' => true,
            ],
            [
                'category_id' => $catMinuman->id,
                'name' => 'Kopi Susu Gula Aren',
                'description' => 'Kopi house blend dengan susu fresh milk.',
                'price' => 18000,
                'image' => null,
                'is_available' => true,
            ],
            [
                'category_id' => $catSnack->id,
                'name' => 'Kentang Goreng',
                'description' => 'French fries renyah dengan saus sambal.',
                'price' => 15000,
                'image' => null,
                'is_available' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
        $this->command->info('✅ 5 Menu Makanan berhasil dibuat!');
    }
}
