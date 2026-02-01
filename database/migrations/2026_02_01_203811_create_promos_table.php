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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Promo Grand Opening"
            $table->string('type'); // 'percentage' atau 'fixed'
            $table->decimal('value', 15, 2); // Nilai (10 untuk 10% atau 5000 untuk Rp 5rb)
            $table->decimal('min_purchase', 15, 2)->default(0); // Syarat minimal belanja
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};