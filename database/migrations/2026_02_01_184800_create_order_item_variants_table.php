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
        Schema::create('order_item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_option_id')->constrained()->onDelete('cascade');
            $table->string('variant_name'); // Snapshot nama grup (misal: Level Pedas)
            $table->string('option_name');  // Snapshot nama opsi (misal: Pedas Mampus)
            $table->decimal('price', 10, 2); // Snapshot harga saat itu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_variants');
    }
};