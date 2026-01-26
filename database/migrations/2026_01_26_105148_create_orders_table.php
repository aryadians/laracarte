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

            // FIX:
            // 1. nullable() -> Agar bisa buat pesanan 'Takeaway' (tanpa meja).
            // 2. nullOnDelete() -> Agar riwayat penjualan tidak hilang kalau meja dihapus.
            $table->foreignId('table_id')
                ->nullable()
                ->constrained('tables')
                ->nullOnDelete();

            $table->string('customer_name');
            $table->text('note')->nullable(); // Catatan (pedas, tanpa bawang, dll)

            // Kolom Total Harga (Penting untuk Laporan)
            $table->decimal('total_price', 15, 2)->default(0);

            // Status pesanan
            $table->enum('status', [
                'pending',   // Baru masuk
                'cooking',   // Sedang dimasak
                'served',    // Sudah disajikan (Siap Bayar)
                'paid',      // Sudah bayar (Lunas)
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
