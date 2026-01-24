<div> <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Daftar Menu & Produk</h1>
            <p class="text-slate-500 mt-1 text-lg">Kelola makanan dan minuman lezatmu di sini.</p>
        </div>
        
        <button wire:click="openModal" class="group relative px-6 py-3 font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all duration-300">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Menu Baru
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
            <input wire:model.live="search" type="text" placeholder="Cari menu nasi goreng, kopi..." class="w-full pl-3 pr-4 py-4 rounded-xl border-none focus:ring-0 text-slate-700 placeholder-slate-400 bg-transparent h-full">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($products as $product)
        <div class="group bg-white rounded-3xl shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-300 transform hover:-translate-y-2 relative border border-slate-100 overflow-hidden">
            
            <div class="h-56 w-full bg-slate-100 relative overflow-hidden group">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $product->name }}">
                @else
                    <div class="flex flex-col items-center justify-center h-full text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-medium uppercase tracking-wider">No Image</span>
                    </div>
                @endif
                
                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full text-xs font-bold text-indigo-600 shadow-sm border border-indigo-50">
                    {{ $product->category->name }}
                </div>
            </div>

            <div class="p-6">
                <h3 class="font-bold text-slate-800 text-lg mb-1 group-hover:text-indigo-600 transition-colors truncate">{{ $product->name }}</h3>
                <p class="text-slate-500 text-sm line-clamp-2 mb-5 h-10 leading-relaxed">{{ $product->description }}</p>
                
                <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                    <span class="font-black text-xl text-slate-800">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    
                    <div class="flex space-x-1 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-all duration-300 transform md:translate-y-2 md:group-hover:translate-y-0">
                        <button wire:click="edit({{ $product->id }})" class="p-2 bg-yellow-50 text-yellow-600 rounded-xl hover:bg-yellow-500 hover:text-white transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                        </button>
                        <button wire:click="delete({{ $product->id }})" wire:confirm="Yakin ingin menghapus?" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>

    @if($isModalOpen)
    <div class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-zoom-in">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">{{ $isEditMode ? 'Edit Menu' : 'Tambah Menu Baru' }}</h3>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Nama Menu</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all text-slate-700">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Kategori</label>
                        <select wire:model="category_id" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-slate-700">
                            <option value="">Pilih...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                         @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Harga</label>
                        <input type="number" wire:model="price" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-slate-700">
                         @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Deskripsi</label>
                    <textarea wire:model="description" rows="3" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-slate-700"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Foto Menu</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:bg-slate-50 transition-colors">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" class="h-32 mx-auto rounded-lg object-cover shadow-md mb-2">
                        @elseif ($oldImage)
                            <img src="{{ asset('storage/' . $oldImage) }}" class="h-32 mx-auto rounded-lg object-cover shadow-md mb-2">
                        @endif
                        
                        <label class="cursor-pointer">
                            <span class="text-indigo-600 font-bold hover:underline">Upload Gambar</span>
                            <input type="file" wire:model="image" class="hidden">
                        </label>
                    </div>
                     <div wire:loading wire:target="image" class="text-xs text-indigo-500 mt-1">Mengupload...</div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 flex justify-end space-x-3">
                <button wire:click="closeModal" class="px-4 py-2 text-slate-600 font-bold hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                <button wire:click="{{ $isEditMode ? 'update' : 'store' }}" class="px-6 py-2 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all font-bold">
                    {{ $isEditMode ? 'Simpan' : 'Tambah' }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>