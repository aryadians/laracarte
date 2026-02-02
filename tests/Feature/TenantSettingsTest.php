<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use App\Livewire\Admin\Settings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class TenantSettingsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    /** @test */
    public function owner_can_update_tenant_branding_settings()
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => \App\Enums\UserRole::OWNER]);
        $tenant = $user->tenant;

        $newLogo = UploadedFile::fake()->image('logo.png');

        Livewire::actingAs($user)
            ->test(Settings::class)
            ->set('storeName', 'Updated Dapur Sedap')
            ->set('storeAddress', 'Jl. Baru No. 99')
            ->set('logo', $newLogo)
            // Required operational settings
            ->set('taxRate', 10)
            ->set('serviceCharge', 5)
            ->set('printerName', 'Thermal_X')
            ->set('midtransIsProduction', false)
            ->call('updateSettings')
            ->assertHasNoErrors();

        $tenant->refresh();

        $this->assertEquals('Updated Dapur Sedap', $tenant->name);
        $this->assertEquals('Jl. Baru No. 99', $tenant->address);
        $this->assertNotNull($tenant->logo);
        
        Storage::disk('public')->assertExists($tenant->logo);
    }
}
