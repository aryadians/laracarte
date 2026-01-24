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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Meja 1", "Meja Outdoor 2"
            $table->string('slug')->unique(); // Kode unik untuk QR, misal: 't1-xyz'
            $table->enum('status', ['empty', 'filled'])->default('empty'); // Status meja
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
