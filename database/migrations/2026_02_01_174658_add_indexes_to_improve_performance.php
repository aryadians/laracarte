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
        Schema::table('orders', function (Blueprint $table) {
            // Index untuk mempercepat filter status (pending, cooking, paid)
            $table->index('status');
            // Index untuk mempercepat laporan/filter tanggal
            $table->index('created_at');
            // Index gabungan untuk pencarian spesifik (misal: paid hari ini)
            $table->index(['status', 'created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            // Index untuk mempercepat filter kategori di menu pelanggan
            $table->index('category_id');
            // Index untuk mempercepat pengecekan stok ketersediaan
            $table->index('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['is_available']);
        });
    }
};