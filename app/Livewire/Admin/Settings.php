<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;

class Settings extends Component
{
    public $storeName;
    public $storeAddress;
    public $taxRate;
    public $serviceCharge;
    public $printerName;
    
    // Midtrans
    public $midtransServerKey;
    public $midtransClientKey;
    public $midtransIsProduction;

    protected $rules = [
        'storeName' => 'required|string|max:100',
        'storeAddress' => 'required|string|max:255',
        'taxRate' => 'required|numeric|min:0|max:100',
        'serviceCharge' => 'required|numeric|min:0|max:100',
        'printerName' => 'nullable|string|max:100',
        'midtransServerKey' => 'nullable|string',
        'midtransClientKey' => 'nullable|string',
        'midtransIsProduction' => 'required|boolean',
    ];

    public function mount()
    {
        $this->storeName = Setting::value('store_name', 'LaraCarte Resto');
        $this->storeAddress = Setting::value('store_address', '-');
        $this->taxRate = Setting::value('tax_rate', 11);
        $this->serviceCharge = Setting::value('service_charge', 5);
        $this->printerName = Setting::value('printer_name', 'Thermal_Printer');
        
        $this->midtransServerKey = Setting::value('midtrans_server_key', '');
        $this->midtransClientKey = Setting::value('midtrans_client_key', '');
        $this->midtransIsProduction = (bool) Setting::value('midtrans_is_production', 0);
    }

    public function updateSettings()
    {
        $this->validate();

        Setting::updateOrCreate(['key' => 'store_name'], ['value' => $this->storeName]);
        Setting::updateOrCreate(['key' => 'store_address'], ['value' => $this->storeAddress]);
        Setting::updateOrCreate(['key' => 'tax_rate'], ['value' => $this->taxRate]);
        Setting::updateOrCreate(['key' => 'service_charge'], ['value' => $this->serviceCharge]);
        Setting::updateOrCreate(['key' => 'printer_name'], ['value' => $this->printerName]);
        
        Setting::updateOrCreate(['key' => 'midtrans_server_key'], ['value' => $this->midtransServerKey]);
        Setting::updateOrCreate(['key' => 'midtrans_client_key'], ['value' => $this->midtransClientKey]);
        Setting::updateOrCreate(['key' => 'midtrans_is_production'], ['value' => $this->midtransIsProduction]);

        session()->flash('message', 'Pengaturan berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('components.admin-layout', ['header' => 'Pengaturan Toko']);
    }
}
