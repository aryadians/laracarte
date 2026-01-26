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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel 'tables' (pastikan tabel tables sudah ada)
            $table->foreignId('table_id')->constrained()->cascadeOnDelete();

            $table->string('customer_name');
            $table->text('note')->nullable(); // Catatan opsional
            $table->decimal('total_price', 12, 2)->default(0); // Total harga

            // Status pesanan
            $table->enum('status', [
                'pending',   // Baru masuk
                'cooking',   // Sedang dimasak
                'served',    // Sudah disajikan
                'paid',      // Sudah bayar
                'completed', // Selesai
                'cancelled'  // Dibatalkan
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
