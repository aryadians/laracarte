<?php

namespace App\Livewire\Front;

// Kiosk akan mewarisi semua logika dari OrderPage agar tidak duplikasi kode
class Kiosk extends OrderPage
{
    public function render()
    {
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::query()
            ->with(['variants'])
            ->where('is_available', true)
            ->when($this->activeCategory !== 'all', function ($q) {
                $q->where('category_id', $this->activeCategory);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.front.kiosk', [
            'categories' => $categories,
            'products' => $products
        ])->layout('layouts.kiosk'); // Gunakan layout khusus Kiosk
    }
}