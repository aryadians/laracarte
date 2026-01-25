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
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->string('customer_name')->nullable(); // Nama pemesan
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('status', ['pending', 'completed', 'paid'])->default('pending');
            $table->string('payment_method')->nullable(); // cash, qris, transfer
            $table->text('note')->nullable(); // Catatan umum pesanan
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
