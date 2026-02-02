<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\PointTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class Loyalty extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCustomer = null;
    public $showHistoryModal = false;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showHistory($customerId)
    {
        $this->selectedCustomer = Customer::with(['pointTransactions' => function($query) {
            $query->latest();
        }])->findOrFail($customerId);
        $this->showHistoryModal = true;
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy('points_balance', 'desc')
            ->paginate(10);

        return view('livewire.admin.loyalty', [
            'customers' => $customers
        ])->layout('components.admin-layout', ['header' => 'Manajemen Member & Loyalitas']);
    }
}
