<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update Tabel Products (Cek dulu biar gak error Duplicate Column)
        Schema::table('products', function (Blueprint $table) {

            // Cek apakah kolom stock SUDAH ADA? Jika BELUM, baru buat.
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('price');
            }

            // Cek apakah kolom min_stock SUDAH ADA? Jika BELUM, baru buat.
            if (!Schema::hasColumn('products', 'min_stock')) {
                $table->integer('min_stock')->default(5)->after('price');
            }
        });

        // 2. Update Tabel Orders (Tambah kolom pajak & stok)
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->default(0)->after('status');
            }

            if (!Schema::hasColumn('orders', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->default(0)->after('subtotal');
            }

            if (!Schema::hasColumn('orders', 'service_charge')) {
                $table->decimal('service_charge', 15, 2)->default(0)->after('tax_amount');
            }

            if (!Schema::hasColumn('orders', 'stock_reduced')) {
                $table->boolean('stock_reduced')->default(false)->after('note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus kolom hanya jika ada
            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }
            if (Schema::hasColumn('products', 'min_stock')) {
                $table->dropColumn('min_stock');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            $columns = ['subtotal', 'tax_amount', 'service_charge', 'stock_reduced'];
            $table->dropColumn(array_intersect($columns, Schema::getColumnListing('orders')));
        });
    }
};
