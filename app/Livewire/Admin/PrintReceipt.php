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

    public function mount($orderId)
    {
        $this->order = Order::with(['items.product', 'table'])->findOrFail($orderId);
        
        // Ambil data toko dari Setting
        $this->storeName = Setting::value('store_name', 'LaraCarte Resto');
        $this->storeAddress = Setting::value('store_address', 'Jl. Digital No. 1');
    }

    public function render()
    {
        return view('livewire.admin.print-receipt')
            ->layout('layouts.print'); // Gunakan layout khusus print
    }
}
