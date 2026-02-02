<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Order;
use App\Livewire\SuperAdmin\Dashboard;
use App\Livewire\SuperAdmin\Tenants;
use Livewire\Livewire;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuperAdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function super_admin_can_access_dashboard_and_see_global_stats()
    {
        // Create 2 tenants with some data
        $tenant1 = Tenant::factory()->create(['name' => 'Resto A']);
        $tenant2 = Tenant::factory()->create(['name' => 'Resto B']);

        Order::factory()->create(['tenant_id' => $tenant1->id, 'total_price' => 100000, 'status' => 'paid']);
        Order::factory()->create(['tenant_id' => $tenant2->id, 'total_price' => 200000, 'status' => 'paid']);

        $superAdmin = User::factory()->create([
            'role' => \App\Enums\UserRole::SUPER_ADMIN,
            'tenant_id' => null
        ]);

        Livewire::actingAs($superAdmin)
            ->test(Dashboard::class)
            ->assertSet('totalTenants', 2)
            ->assertSet('totalOrders', 2)
            ->assertSet('totalRevenue', 300000)
            ->assertSee('Total Tenant')
            ->assertSee('Omzet Global');

        $this->actingAs($superAdmin)
            ->get(route('super-admin.dashboard'))
            ->assertStatus(200)
            ->assertSee('Platform Admin')
            ->assertSee('Pusat Statistik');
    }

    /** @test */
    public function super_admin_can_impersonate_tenant_owner_and_return()
    {
        $tenant = Tenant::factory()->create(['name' => 'Target Resto']);
        $owner = User::factory()->create([
            'role' => \App\Enums\UserRole::OWNER,
            'tenant_id' => $tenant->id,
            'name' => 'John Owner'
        ]);

        $superAdmin = User::factory()->create([
            'role' => \App\Enums\UserRole::SUPER_ADMIN,
            'tenant_id' => null
        ]);

        // Start Impersonation
        Livewire::actingAs($superAdmin)
            ->test(Tenants::class)
            ->call('impersonate', $tenant->id)
            ->assertRedirect(route('dashboard'));

        // Verify we are logged in as owner
        $this->assertEquals($owner->id, Auth::id());
        $this->assertTrue(session()->has('impersonator_id'));

        // Verify Banner visibility
        $this->get(route('dashboard'))
            ->assertSee('Mode Impersonasi')
            ->assertSee('John Owner')
            ->assertSee('Balik ke Super Admin');

        // Leave Impersonation
        $this->post(route('impersonate.leave'))
            ->assertRedirect(route('super-admin.dashboard'));

        // Verify we are back as super admin
        $this->assertEquals($superAdmin->id, auth()->id());
        $this->assertFalse(session()->has('impersonator_id'));
    }
}
