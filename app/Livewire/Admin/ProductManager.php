<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads; // Wajib untuk upload gambar
use Livewire\WithPagination;  // Wajib untuk halaman
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductManager extends Component
{
    use WithFileUploads, WithPagination;

    // Variabel Form
    public $name, $price, $description, $category_id, $image, $product_id;
    public $oldImage; // Untuk menyimpan nama gambar lama saat edit

    // --- TAMBAHAN BARU (FITUR STOK) ---
    public $stock = 10;           // Default stok awal
    public $is_available = true;  // Default status aktif
    // ----------------------------------

    // Variabel UI
    public $isModalOpen = false;
    public $isEditMode = false;
    public $search = '';

    // Rules Validasi
    protected $rules = [
        'name' => 'required|min:3',
        'category_id' => 'required',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable',
        'image' => 'nullable|image|max:2048', // Max 2MB
        'stock' => 'required|numeric|min:0',  // Validasi Stok
    ];

    // Reset halaman saat searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Tampilkan Data
    public function render()
    {
        return view('livewire.admin.product-manager', [
            'products' => Product::with('category') // Eager load category biar ringan
                ->where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(8),
            'categories' => Category::all(),
        ])->layout('components.admin-layout', ['header' => 'Produk & Menu']);
    }

    // Buka Modal Tambah
    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
        $this->isEditMode = false;
    }

    // Tutup Modal
    public function closeModal()
    {
        $this->isModalOpen = false;
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

        // Reset Stok & Status ke Default
        $this->stock = 10;
        $this->is_available = true;
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
            'stock' => $this->stock,              // Simpan Stok
            'is_available' => $this->is_available // Simpan Status
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

        // Load Data Stok & Status Lama
        $this->stock = $product->stock;
        $this->is_available = (bool) $product->is_available; // Pastikan boolean

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    // Update Data
    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'stock' => 'required|numeric|min:0', // Validasi Stok saat update
        ]);

        $product = Product::findOrFail($this->product_id);

        $imagePath = $this->oldImage;
        if ($this->image) {
            // Hapus gambar lama jika ada dan file baru diupload
            if ($this->oldImage) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('products', 'public');
        }

        $product->update([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'description' => $this->description,
            'image' => $imagePath,
            'stock' => $this->stock,              // Update Stok
            'is_available' => $this->is_available // Update Status
        ]);

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
