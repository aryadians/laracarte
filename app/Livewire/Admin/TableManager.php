<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Table;
use Illuminate\Support\Str;

class TableManager extends Component
{
    public $name; // Nama Meja (misal: Meja 1)

    // Validasi
    protected $rules = [
        'name' => 'required|min:3|unique:tables,name',
    ];

    // Tambah Meja Baru
    public function store()
    {
        $this->validate();

        Table::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . Str::random(5),
            'status' => 'empty'
        ]);

        $this->reset('name');
        session()->flash('message', 'Meja berhasil ditambahkan!');
    }

    // Hapus Meja
    public function delete($id)
    {
        Table::find($id)->delete();
        session()->flash('message', 'Meja dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.table-manager', [
            'tables' => Table::latest()->get()
        ])->layout('components.admin-layout', ['header' => 'Manajemen Meja & QR']);
    }
}
