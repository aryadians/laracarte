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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Misal: Voucher 50rb
            $table->text('description')->nullable();
            $table->integer('points_required'); // Harga poin (misal 500)
            $table->string('type'); // 'discount_fixed' (potongan harga) atau 'free_item' (gratis produk)
            $table->decimal('value', 15, 2)->nullable(); // Nilai potongan (misal 50000)
            $table->foreignId('free_product_id')->nullable()->constrained('products')->nullOnDelete(); // Jika hadiah produk
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};