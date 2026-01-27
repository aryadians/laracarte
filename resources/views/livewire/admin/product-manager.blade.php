<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Produk 🍔</h1>
            <p class="text-slate-500 mt-1 text-lg">Kelola menu, stok, dan harga produk di sini.</p>
        </div>
        
        <button wire:click="create" class="group relative px-6 py-3 font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all duration-300">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Produk Baru
            </span>
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl shadow-sm flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-bold text-green-800">Berhasil!</p>
                    <p class="text-sm text-green-700">{{ session('message') }}</p>
                </div>
            </div>
            <button wire:click="$set('search', '')" class="text-green-500 hover:text-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <div class="mb-8 relative group">
        <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl opacity-20 group-hover:opacity-40 transition duration-500 blur"></div>
        <div class="relative flex items-center bg-white rounded-xl shadow-sm">
            <div class="pl-4 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari menu nasi goreng, kopi..." class="w-full pl-3 pr-4 py-4 rounded-xl border-none focus:ring-0 text-slate-700 placeholder-slate-400 bg-transparent h-full">
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Gambar</th>
                    <th class="px-6 py-4">Nama Produk</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Stok (Inventory)</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 rounded-lg object-cover shadow-sm border border-slate-100">
                        @else
                            <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $product->name }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded-lg text-xs font-bold">{{ $product->category->name }}</span>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-700">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    
                    <td class="px-6 py-4">
                        <div class="flex flex-col items-start">
                            <span class="font-black text-sm {{ $product->stock <= $product->min_stock ? 'text-red-600' : 'text-slate-700' }}">
                                {{ $product->stock }} Unit
                            </span>
                            @if($product->stock <= $product->min_stock)
                                <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold mt-1 animate-pulse">
                                    Stok Menipis!
                                </span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <button wire:click="edit({{ $product->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 text-xs bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition">Edit</button>
                        <button wire:click="delete({{ $product->id }})" class="text-red-600 hover:text-red-900 font-bold text-xs bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition" onclick="confirm('Yakin ingin menghapus produk ini?') || event.stopImmediatePropagation()">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada produk. Silakan tambah produk baru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 bg-slate-50 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-zoom-in">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-lg font-black text-slate-800">{{ $isEditMode ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto custom-scrollbar">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Produk</label>
                        <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-300" placeholder="Contoh: Nasi Goreng">
                        @error('name') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kategori</label>
                        <select wire:model="category_id" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">Pilih...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga (Rp)</label>
                        <input type="number" wire:model="price" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        @error('price') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok Awal</label>
                        <input type="number" wire:model="stock" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold text-slate-700">
                        @error('stock') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Min. Alert</label>
                        <input type="number" wire:model="min_stock" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm text-slate-500">
                        @error('min_stock') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Deskripsi Menu</label>
                    <textarea wire:model="description" rows="2" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Jelaskan rasa dan bahan..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Foto Menu</label>
                    <div class="flex items-center gap-4">
                        <div class="shrink-0">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="h-16 w-16 rounded-xl object-cover border border-slate-200 shadow-sm">
                            @elseif($oldImage)
                                <img src="{{ asset('storage/' . $oldImage) }}" class="h-16 w-16 rounded-xl object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="h-16 w-16 rounded-xl bg-slate-100 flex items-center justify-center text-slate-300 border border-slate-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <label class="block">
                            <span class="sr-only">Choose profile photo</span>
                            <input type="file" wire:model="image" class="block w-full text-sm text-slate-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-xs file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100 transition-colors
                            "/>
                            <div wire:loading wire:target="image" class="text-xs text-indigo-500 mt-1 font-bold animate-pulse">Mengupload gambar...</div>
                        </label>
                    </div>
                    @error('image') <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
                <button wire:click="closeModal" class="px-4 py-2 text-slate-600 font-bold hover:bg-slate-200 rounded-xl text-sm transition-colors">Batal</button>
                <button wire:click="{{ $isEditMode ? 'update' : 'store' }}" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-indigo-500/30 flex items-center gap-2">
                    <span wire:loading.remove wire:target="{{ $isEditMode ? 'update' : 'store' }}">{{ $isEditMode ? 'Simpan Perubahan' : 'Simpan Produk' }}</span>
                    <span wire:loading wire:target="{{ $isEditMode ? 'update' : 'store' }}">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>