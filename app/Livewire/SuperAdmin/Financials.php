<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;

use App\Models\Order;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

class Financials extends Component
{
    public $totalRevenue;
    public $totalOrders;
    public $activeTenants;
    public $topRestaurants = [];
    public $monthlyStats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // 1. Core Platform Stats
        $this->totalRevenue = Order::where('status', 'paid')->sum('total_price');
        $this->totalOrders = Order::where('status', 'paid')->count();
        $this->activeTenants = Tenant::where('is_active', true)->count();

        // 2. Top Restaurants by Revenue
        $this->topRestaurants = Order::select('tenant_id', DB::raw('SUM(total_price) as total_revenue'))
            ->where('status', 'paid')
            ->groupBy('tenant_id')
            ->orderByDesc('total_revenue')
            ->with('tenant')
            ->take(5)
            ->get();

        // 3. Monthly Global Revenue (Last 6 Months)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        
        $query = Order::select(
            $isSqlite ? DB::raw("strftime('%m', created_at) as month") : DB::raw('MONTH(created_at) as month'),
            $isSqlite ? DB::raw("strftime('%Y', created_at) as year") : DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total_price) as total')
        )
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $this->monthlyStats = $query->map(function ($item) {
                return [
                    'label' => Carbon::create()->month((int)$item->month)->format('F') . ' ' . $item->year,
                    'total' => $item->total,
                ];
            });
    }

    #[Layout('components.admin-layout')]
    public function render()
    {
        return view('livewire.super-admin.financials')
            ->layout('components.admin-layout', ['header' => 'Keuangan Platform']);
    }
}
