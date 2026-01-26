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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel 'orders'
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            // Relasi ke tabel 'products'
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->integer('quantity');

            // Menyimpan harga saat transaksi terjadi (Snapshot Price)
            // Penting agar jika harga produk berubah nanti, data sejarah tidak ikut berubah
            $table->decimal('price', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
