<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ingredient;

class IngredientManager extends Component
{
    use WithPagination;

    public $name, $unit, $stock, $min_stock, $ingredient_id;
    public $isOpen = false;
    public $isEditMode = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|min:3',
        'unit' => 'required',
        'stock' => 'required|numeric|min:0',
        'min_stock' => 'required|numeric|min:0',
    ];

    public function render()
    {
        $ingredients = Ingredient::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.ingredient-manager', [
            'ingredients' => $ingredients
        ])->layout('components.admin-layout', ['header' => 'Bahan Baku']);
    }

    public function create()
    {
        $this->resetInput();
        $this->isEditMode = false;
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $this->ingredient_id = $id;
        $this->name = $ingredient->name;
        $this->unit = $ingredient->unit;
        $this->stock = $ingredient->stock;
        $this->min_stock = $ingredient->min_stock;
        $this->isEditMode = true;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();
        Ingredient::create([
            'name' => $this->name,
            'unit' => $this->unit,
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
        ]);
        session()->flash('message', 'Bahan baku berhasil ditambah.');
        $this->isOpen = false;
    }

    public function update()
    {
        $this->validate();
        $ingredient = Ingredient::findOrFail($this->ingredient_id);
        $ingredient->update([
            'name' => $this->name,
            'unit' => $this->unit,
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
        ]);
        session()->flash('message', 'Bahan baku berhasil diupdate.');
        $this->isOpen = false;
    }

    public function delete($id)
    {
        Ingredient::findOrFail($id)->delete();
        session()->flash('message', 'Bahan baku dihapus.');
    }

    private function resetInput()
    {
        $this->name = '';
        $this->unit = 'gram';
        $this->stock = 0;
        $this->min_stock = 10;
    }
}