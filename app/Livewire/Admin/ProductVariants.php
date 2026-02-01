<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use App\Models\Ingredient;

class ProductVariants extends Component
{
    public $product;
    
    // Form Varian Baru
    public $newVariantName;
    public $newVariantType = 'radio'; // radio/checkbox
    public $newVariantRequired = false;

    // Form Opsi Baru
    public $activeVariantId = null; // ID varian yang sedang diedit opsinya
    public $newOptionName;
    public $newOptionPrice = 0;

    // Form Resep Baru
    public $selectedIngredientId;
    public $neededQuantity;

    public $activeTab = 'variants'; // variants | recipe

    public function mount($productId)
    {
        $this->product = Product::findOrFail($productId);
    }

    public function render()
    {
        return view('livewire.admin.product-variants', [
            'variants' => $this->product->variants()->with('options')->get(),
            'allIngredients' => Ingredient::orderBy('name')->get(),
            'recipe' => $this->product->ingredients()->get()
        ]);
    }

    // --- CRUD VARIANT ---
    public function addVariant()
    {
        $this->validate([
            'newVariantName' => 'required|min:3',
            'newVariantType' => 'required|in:radio,checkbox'
        ]);

        $this->product->variants()->create([
            'name' => $this->newVariantName,
            'type' => $this->newVariantType,
            'is_required' => $this->newVariantRequired
        ]);

        $this->reset(['newVariantName', 'newVariantType', 'newVariantRequired']);
        session()->flash('message', 'Varian ditambahkan.');
    }

    public function deleteVariant($id)
    {
        ProductVariant::find($id)->delete();
        session()->flash('message', 'Varian dihapus.');
    }

    // --- CRUD OPTION ---
    public function setActiveVariant($id)
    {
        $this->activeVariantId = ($this->activeVariantId == $id) ? null : $id;
    }

    public function addOption($variantId)
    {
        $this->validate([
            'newOptionName' => 'required',
            'newOptionPrice' => 'required|numeric|min:0'
        ]);

        ProductVariantOption::create([
            'product_variant_id' => $variantId,
            'name' => $this->newOptionName,
            'price' => $this->newOptionPrice
        ]);

        $this->reset(['newOptionName', 'newOptionPrice']);
        session()->flash('message', 'Opsi berhasil ditambahkan.');
    }

    public function deleteOption($id)
    {
        ProductVariantOption::find($id)->delete();
        session()->flash('message', 'Opsi dihapus.');
    }

    // --- CRUD RECIPE (INGREDIENTS) ---
    public function addIngredient()
    {
        $this->validate([
            'selectedIngredientId' => 'required',
            'neededQuantity' => 'required|numeric|min:0.01'
        ]);

        $this->product->ingredients()->syncWithoutDetaching([
            $this->selectedIngredientId => ['quantity' => $this->neededQuantity]
        ]);

        $this->reset(['selectedIngredientId', 'neededQuantity']);
        session()->flash('message', 'Bahan ditambahkan ke resep.');
    }

    public function removeIngredient($id)
    {
        $this->product->ingredients()->detach($id);
        session()->flash('message', 'Bahan dihapus dari resep.');
    }
}
