<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Order;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalTenants;
    public $totalUsers;
    public $totalOrders;
    public $totalRevenue;

    public function mount()
    {
        // Stats are fetched without global scope because Super Admin bypass is in trait
        // But to be explicit and safe (in case traits change), we can use withoutGlobalScopes()
        $this->totalTenants = Tenant::count();
        $this->totalUsers = User::count();
        $this->totalOrders = Order::count();
        $this->totalRevenue = Order::where('status', 'paid')->sum('total_price');
    }

    public function render()
    {
        return view('livewire.super-admin.dashboard')
            ->layout('components.admin-layout', ['header' => 'Global Platform Dashboard']);
    }
}
