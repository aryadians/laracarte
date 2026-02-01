<div class="kiosk-container flex h-screen overflow-hidden bg-slate-50" x-data>
    
    {{-- Sidebar Kategori (Kiri) --}}
    <div class="w-32 flex-shrink-0 bg-white border-r border-slate-200 flex flex-col items-center py-8 gap-4 overflow-y-auto no-scrollbar">
        <div class="mb-6">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <button wire:click="setCategory('all')" 
            class="flex flex-col items-center justify-center w-24 h-24 rounded-2xl transition-all gap-2 {{ $activeCategory === 'all' ? 'bg-indigo-600 text-white shadow-xl scale-105' : 'text-slate-400 hover:bg-slate-100' }}">
            <span class="text-2xl">🍽️</span>
            <span class="text-[10px] font-black uppercase tracking-widest">Semua</span>
        </button>

        @foreach($categories as $cat)
        <button wire:click="setCategory({{ $cat->id }})" 
            class="flex flex-col items-center justify-center w-24 h-24 rounded-2xl transition-all gap-2 {{ $activeCategory == $cat->id ? 'bg-indigo-600 text-white shadow-xl scale-105' : 'text-slate-400 hover:bg-slate-100' }}">
            <span class="text-2xl">🍕</span>
            <span class="text-[10px] font-black uppercase tracking-widest text-center px-1">{{ $cat->name }}</span>
        </button>
        @endforeach
    </div>

    {{-- Menu Utama (Tengah) --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-24 bg-white border-b border-slate-200 flex items-center justify-between px-10 shrink-0">
            <div>
                <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Lara<span class="text-indigo-600">Carte</span> Kiosk</h1>
                <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">{{ $table_name }}</p>
            </div>
            <div class="flex items-center gap-4">
                <button wire:click="callWaitress" class="bg-red-50 text-red-600 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest border border-red-100 hover:bg-red-100 transition-colors">
                    Panggil Pelayan
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            @if($products->isEmpty())
                <div class="p-10 text-center text-xl font-bold text-gray-400">TIDAK ADA PRODUK DITEMUKAN</div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($products as $product)
                    <div wire:key="prod-{{ $product->id }}" 
                        wire:click="addToCart({{ $product->id }})"
                        class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-slate-100 group cursor-pointer active:scale-95">
                        
                        <div class="aspect-square relative overflow-hidden bg-slate-100">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            
                            @if($product->variants->isNotEmpty())
                                <div class="absolute top-4 right-4 bg-indigo-600 text-white text-[10px] font-black px-3 py-1.5 rounded-full shadow-lg uppercase tracking-widest">Opsi Tersedia</div>
                            @endif
                        </div>

                        <div class="p-6 text-center">
                            <h3 class="font-black text-slate-800 text-lg leading-tight mb-1">{{ $product->name }}</h3>
                            <p class="text-indigo-600 font-black text-xl">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>

    {{-- Cart Sidebar (Kanan) --}}
    <div class="w-96 flex-shrink-0 bg-white border-l border-slate-200 flex flex-col shadow-2xl z-10">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Pesanan Anda</h2>
            <div class="bg-indigo-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-black text-sm">
                {{ $this->getTotalItems() }}
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
            @forelse($cart as $uuid => $item)
            <div class="flex gap-4 items-start bg-slate-50 p-4 rounded-3xl border border-slate-100">
                <div class="flex-1">
                    <h4 class="font-black text-slate-800 text-sm leading-tight">{{ $item['name'] }}</h4>
                    <p class="text-xs text-slate-400 mt-1">@ Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    @if(!empty($item['variants']))
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach($item['variants'] as $v)
                                <span class="text-[9px] bg-white border border-slate-200 text-slate-500 px-1.5 py-0.5 rounded-md font-bold">{{ $v['option_name'] }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="flex flex-col items-center gap-2">
                    <button wire:click="incrementQty('{{ $uuid }}')" class="w-8 h-8 bg-white border border-slate-200 rounded-xl flex items-center justify-center font-black text-indigo-600 shadow-sm">+</button>
                    <span class="font-black text-slate-800">{{ $item['qty'] }}</span>
                    <button wire:click="decrementQty('{{ $uuid }}')" class="w-8 h-8 bg-white border border-slate-200 rounded-xl flex items-center justify-center font-black text-red-500 shadow-sm">-</button>
                </div>
            </div>
            @empty
            <div class="h-full flex flex-col items-center justify-center opacity-20 py-20">
                <span class="text-6xl mb-4">🛒</span>
                <p class="font-black uppercase tracking-widest text-xs">Keranjang Kosong</p>
            </div>
            @endforelse
        </div>

        <div class="p-8 border-t border-slate-100 bg-slate-50 space-y-4">
            <div class="flex justify-between items-end">
                <span class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Total Bayar</span>
                <span class="text-3xl font-black text-indigo-600">Rp {{ number_format($this->getGrandTotal(), 0, ',', '.') }}</span>
            </div>
            
            <button wire:click="openCheckout" @disabled(empty($cart)) class="w-full bg-slate-900 hover:bg-black text-white py-5 rounded-[2rem] font-black text-lg shadow-xl shadow-slate-900/20 transition-all active:scale-95 disabled:opacity-50">
                BAYAR SEKARANG
            </button>
        </div>
    </div>

    {{-- REUSE MODAL DARI ORDER PAGE (Variant & Checkout) --}}
    @include('livewire.front.kiosk-modals')

</div>