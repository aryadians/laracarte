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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Jika kategori dihapus, produk ikut hilang
        $table->string('name');
        $table->string('description')->nullable();
        $table->decimal('price', 10, 2); // Format harga aman
        $table->string('image')->nullable();
        $table->boolean('is_available')->default(true); // Untuk stok habis
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
