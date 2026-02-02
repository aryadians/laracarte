<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\User;
use App\Livewire\SuperAdmin\Financials;
use App\Livewire\SuperAdmin\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SuperAdminFinancialsTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_financials_and_see_aggregated_data(): void
    {
        $superAdmin = User::factory()->create(['role' => UserRole::SUPER_ADMIN, 'tenant_id' => null]);
        
        $tenant1 = Tenant::factory()->create(['name' => 'Resto A']);
        $tenant2 = Tenant::factory()->create(['name' => 'Resto B']);

        Order::factory()->create(['tenant_id' => $tenant1->id, 'total_price' => 100000, 'status' => 'paid']);
        Order::factory()->create(['tenant_id' => $tenant2->id, 'total_price' => 200000, 'status' => 'paid']);

        Livewire::actingAs($superAdmin)
            ->test(Financials::class)
            ->assertSet('totalRevenue', 300000)
            ->assertSet('totalOrders', 2)
            ->assertSee('Resto A')
            ->assertSee('Resto B');
    }

    public function test_super_admin_can_update_platform_settings(): void
    {
        $superAdmin = User::factory()->create(['role' => UserRole::SUPER_ADMIN, 'tenant_id' => null]);

        Livewire::actingAs($superAdmin)
            ->test(Settings::class)
            ->set('appName', 'New Platform Name')
            ->set('supportEmail', 'new@support.com')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('global_settings', [
            'key' => 'app_name',
            'value' => 'New Platform Name'
        ]);
        
        $this->assertDatabaseHas('global_settings', [
            'key' => 'support_email',
            'value' => 'new@support.com'
        ]);
    }
}
