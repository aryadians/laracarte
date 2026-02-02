<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class EmployeeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_see_employee_menu(): void
    {
        $tenant = Tenant::factory()->create();
        $owner = User::factory()->create(['tenant_id' => $tenant->id, 'role' => UserRole::OWNER->value]);

        $this->actingAs($owner);

        // Assuming we will have a route 'admin.employees'
        $response = $this->get(route('dashboard'));
        
        // We expect to see "Karyawan" or similar in the dashboard navigation
        $response->assertSee('Manajemen Karyawan'); 
    }

    public function test_owner_can_create_new_employee(): void
    {
        $tenant = Tenant::factory()->create();
        $owner = User::factory()->create(['tenant_id' => $tenant->id, 'role' => UserRole::OWNER->value]);

        $this->actingAs($owner);

        // We will mock the component creation roughly to ensure the logic exists
        // But for now, let's verify the backend logic via specific interaction
        // Ideally we test the Volt component directly
        
        $component = Volt::test('admin.employees.create')
            ->set('name', 'John Cashier')
            ->set('email', 'cashier@example.com')
            ->set('role', UserRole::CASHIER->value)
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('save');

        $component->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'cashier@example.com',
            'role' => UserRole::CASHIER->value,
            'tenant_id' => $tenant->id, // Critical: Must be same tenant
        ]);
        
        $this->assertDatabaseMissing('users', [
             'email' => 'cashier@example.com',
             'tenant_id' => null
        ]);
    }
}
