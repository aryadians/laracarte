<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add tenant_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Add tenant_id to other tables
        $tables = [
            'products',
            'categories',
            'tables',
            'orders',
            'customers',
            'ingredients',
            'settings',
            'promos',
            'rewards',
            'point_transactions',
            'waitress_calls'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // We use nullable initially to ease migration, but ideally should be required
                $table->foreignId('tenant_id')->nullable()->index()->constrained()->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        $tables = [
            'products',
            'categories',
            'tables',
            'orders',
            'customers',
            'ingredients',
            'settings',
            'promos',
            'rewards',
            'point_transactions',
            'waitress_calls'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
