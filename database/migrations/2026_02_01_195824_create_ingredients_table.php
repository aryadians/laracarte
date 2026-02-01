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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Beras, Telur, Gula
            $table->string('unit'); // Contoh: gram, butir, ml
            $table->decimal('stock', 15, 2)->default(0); // Stok saat ini
            $table->decimal('min_stock', 15, 2)->default(10); // Alert stok menipis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};