<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Tenants extends Component
{
    use WithPagination;

    public $search = '';

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function impersonate($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $owner = User::where('tenant_id', $tenant->id)
            ->where('role', \App\Enums\UserRole::OWNER)
            ->first();

        if (!$owner) {
            session()->flash('error', "Tenant ini tidak memiliki Owner untuk di-impersonate.");
            return;
        }

        // Simpan ID Super Admin asli untuk nanti balik lagi
        session()->put('impersonator_id', auth()->user()->id);

        auth()->login($owner);

        return redirect()->route('dashboard');
    }

    public function toggleStatus($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $tenant->update([
            'is_active' => !$tenant->is_active
        ]);

        session()->flash('message', "Status {$tenant->name} berhasil diperbarui.");
    }

    public function render()
    {
        $tenants = Tenant::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('slug', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.super-admin.tenants', [
            'tenants' => $tenants
        ])->layout('components.admin-layout', ['header' => 'Manajemen Restoran']);
    }
}
