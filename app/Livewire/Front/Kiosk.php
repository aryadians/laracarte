<?php

namespace App\Livewire\Front;

use App\Models\Table;
use App\Models\Category;
use App\Models\Product;

// Kiosk mewarisi OrderPage untuk Cart Logic, tapi kita override render & mount
class Kiosk extends OrderPage
{
    public function mount($slug)
    {
        // Panggil parent mount untuk setup table & session
        parent::mount($slug);
        
        // Pastikan activeCategory default
        $this->activeCategory = 'all';
    }

    public function render()
    {
        $categories = Category::all();
        
        $query = Product::query()
            ->with(['variants'])
            ->where('is_available', true);

        if ($this->activeCategory !== 'all') {
            $query->where('category_id', $this->activeCategory);
        }

        $products = $query->orderBy('name')->get();

        return view('livewire.front.kiosk', [
            'categories' => $categories,
            'products' => $products
        ])->layout('layouts.kiosk');
    }
}
