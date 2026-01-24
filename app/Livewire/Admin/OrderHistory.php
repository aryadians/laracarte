<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class OrderHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $date; // Filter Tanggal

    public function render()
    {
        $query = Order::with(['items.product', 'table'])
            ->where('status', 'paid') // Hanya yang sudah bayar
            ->latest();

        // Filter Pencarian (Nama Customer / ID Order)
        if ($this->search) {
            $query->where('customer_name', 'like', '%' . $this->search . '%')
                ->orWhere('id', $this->search);
        }

        // Filter Tanggal
        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        return view('livewire.admin.order-history', [
            'orders' => $query->paginate(10)
        ])->layout('components.admin-layout', ['header' => 'Riwayat Transaksi']);
    }

    // Reset Filter
    public function resetFilters()
    {
        $this->reset(['search', 'date']);
    }
}
