<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Promo;

class PromoManager extends Component
{
    public $name, $type = 'percentage', $value, $min_purchase = 0, $is_active = true, $promo_id;
    public $isOpen = false;
    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|min:3',
        'type' => 'required|in:percentage,fixed',
        'value' => 'required|numeric|min:0',
        'min_purchase' => 'required|numeric|min:0',
    ];

    public function render()
    {
        return view('livewire.admin.promo-manager', [
            'promos' => Promo::latest()->get()
        ])->layout('components.admin-layout', ['header' => 'Manajemen Promo']);
    }

    public function create()
    {
        $this->resetInput();
        $this->isEditMode = false;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();
        Promo::create([
            'name' => $this->name,
            'type' => $this->type,
            'value' => $this->value,
            'min_purchase' => $this->min_purchase,
            'is_active' => $this->is_active,
        ]);
        session()->flash('message', 'Promo berhasil dibuat!');
        $this->isOpen = false;
    }

    public function edit($id)
    {
        $promo = Promo::findOrFail($id);
        $this->promo_id = $id;
        $this->name = $promo->name;
        $this->type = $promo->type;
        $this->value = $promo->value;
        $this->min_purchase = $promo->min_purchase;
        $this->is_active = $promo->is_active;
        $this->isEditMode = true;
        $this->isOpen = true;
    }

    public function update()
    {
        $this->validate();
        Promo::find($this->promo_id)->update([
            'name' => $this->name,
            'type' => $this->type,
            'value' => $this->value,
            'min_purchase' => $this->min_purchase,
            'is_active' => $this->is_active,
        ]);
        session()->flash('message', 'Promo berhasil diupdate!');
        $this->isOpen = false;
    }

    public function toggleStatus($id)
    {
        $promo = Promo::find($id);
        $promo->update(['is_active' => !$promo->is_active]);
    }

    public function delete($id)
    {
        Promo::find($id)->delete();
    }

    private function resetInput()
    {
        $this->name = '';
        $this->type = 'percentage';
        $this->value = '';
        $this->min_purchase = 0;
        $this->is_active = true;
    }
}