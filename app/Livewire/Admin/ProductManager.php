<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductManager extends Component
{
    use WithFileUploads, WithPagination;

    // Variabel Form
    public $name, $price, $description, $category_id, $image, $product_id;
    public $oldImage; // Untuk menyimpan nama gambar lama saat edit

    // --- FITUR STOK (INVENTORY) ---
    public $stock = 0;       // Stok saat ini
    public $min_stock = 5;   // Batas peringatan stok menipis
    // ------------------------------

    // Variabel UI
    public $isOpen = false;
    public $isEditMode = false;
    public $isVariantModalOpen = false;
    public $selectedProductIdForVariants = null;
    public $search = '';

    // ...

    public function manageVariants($productId)
    {
        $this->selectedProductIdForVariants = $productId;
        $this->isVariantModalOpen = true;
    }

    public function closeVariantModal()
    {
        $this->isVariantModalOpen = false;
        $this->selectedProductIdForVariants = null;
    }

    // Rules Validasi
    protected $rules = [
        'name' => 'required|min:3',
        'category_id' => 'required',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable',
        'image' => 'nullable|image|max:2048', // Max 2MB

        // Validasi Stok
        'stock' => 'required|integer|min:0',
        'min_stock' => 'required|integer|min:0',
    ];

    // Reset halaman saat searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Tampilkan Data
    public function render()
    {
        $products = Product::with('category')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.product-manager', [
            'products' => $products,
            'categories' => Category::all(),
        ])->layout('components.admin-layout', ['header' => 'Manajemen Produk']);
    }

    // Buka Modal Tambah
    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    // Buka Modal
    public function openModal()
    {
        $this->isOpen = true;
    }

    // Tutup Modal
    public function closeModal()
    {
        $this->isOpen = false;
    }

    // Reset Input
    private function resetInputFields()
    {
        $this->name = '';
        $this->price = '';
        $this->description = '';
        $this->category_id = '';
        $this->image = null;
        $this->oldImage = null;
        $this->product_id = null;

        // Reset Stok ke Default
        $this->stock = 0;
        $this->min_stock = 5;
        $this->isEditMode = false;
    }

    // Simpan Data (Create)
    public function store()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        Product::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'description' => $this->description,
            'image' => $imagePath,
            // Simpan Stok
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
        ]);

        session()->flash('message', '✨ Produk berhasil ditambahkan!');
        $this->closeModal();
        $this->resetInputFields();
    }

    // Persiapan Edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->price = $product->price;
        $this->description = $product->description;
        $this->oldImage = $product->image;

        // Load Data Stok Lama
        $this->stock = $product->stock;
        $this->min_stock = $product->min_stock;

        $this->isEditMode = true;
        $this->openModal();
    }

    // Update Data
    public function update()
    {
        $this->validate();

        $product = Product::findOrFail($this->product_id);

        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'description' => $this->description,
            // Update Stok
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
        ];

        // Cek jika ada gambar baru
        if ($this->image) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $this->image->store('products', 'public');
        }

        $product->update($data);

        session()->flash('message', '🚀 Produk berhasil diperbarui!');
        $this->closeModal();
        $this->resetInputFields();
    }

    // Hapus Data
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        session()->flash('message', '🗑️ Produk dihapus dari daftar.');
    }
}
