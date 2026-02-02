<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Setting;

class PrintReceipt extends Component
{
    public $order;
    public $storeName;
    public $storeAddress;
    public $logo;

    public function mount($orderId)
    {
        $this->order = Order::with(['items.product', 'table'])->findOrFail($orderId);
        
        // Ambil data toko dari Tenant terkait Order
        $tenant = $this->order->tenant;
        $this->storeName = $tenant->name ?? 'LaraCarte Resto';
        $this->storeAddress = $tenant->address ?? '-';
        $this->logo = $tenant->logo;
    }

    public function render()
    {
        return view('livewire.admin.print-receipt')
            ->layout('layouts.print'); // Gunakan layout khusus print
    }
}
