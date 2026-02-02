<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Str;

class FixLegacyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Check if there are users without tenant
        $usersWithoutTenant = User::whereNull('tenant_id')->get();

        if ($usersWithoutTenant->isEmpty()) {
            $this->command->info('No legacy users found to fix.');
            return;
        }

        $this->command->info('Found ' . $usersWithoutTenant->count() . ' users without tenant.');

        // 2. Create a default tenant for them (or use existing)
        $tenant = Tenant::firstOrCreate(
            ['name' => 'Default Restaurant'],
            [
                'slug' => 'default-restaurant-' . Str::random(4),
                'address' => 'Default Address',
            ]
        );

        // 3. Update users and their related data
        foreach ($usersWithoutTenant as $user) {
            $user->update(['tenant_id' => $tenant->id]);
            $this->command->info("Assigned user {$user->email} to tenant '{$tenant->name}'");

            // Optional: If you want to assign ALL existing data to this tenant as well?
            // This might be dangerous if multiple independent datasets existed, but safe for single-tenant -> SaaS migration
            // Since we used nullable foreign keys in migration, many rows might be null.
        }

        // 4. Fix other tables (Assign ALL NULL tenant_id records to this default tenant)
        // This assumes all current data belongs to this "first" restaurant.
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

        foreach ($tables as $table) {
            \Illuminate\Support\Facades\DB::table($table)
                ->whereNull('tenant_id')
                ->update(['tenant_id' => $tenant->id]);

            $this->command->info("Fixed NULL tenant_id for table: {$table}");
        }
    }
}
