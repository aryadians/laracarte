<div class="relative min-h-screen pb-32" x-data>

    <!-- Global Notification (e.g., Call Waitress) -->
    @if (session()->has('info'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="fixed top-5 left-1/2 -translate-x-1/2 z-[150] w-full max-w-sm px-4">
            <div class="p-3 rounded-2xl bg-slate-900/95 backdrop-blur-sm text-white font-bold text-sm text-center shadow-2xl shadow-slate-900/40 border border-slate-700/50">
                {{ session('info') }}
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="sticky top-0 z-40 bg-[#F6F8FC]/95 backdrop-blur-md border-b border-slate-200/60 shadow-sm transition-all">
        <div class="px-5 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h1 class="text-lg font-black text-slate-800 leading-none tracking-tight">Lara<span class="text-indigo-600">Carte</span></h1>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <p class="text-[0.65rem] text-slate-500 font-bold uppercase tracking-widest">{{ $table_name }}</p>
                    </div>
                </div>
            </div>

            <!-- Call Waitress Button -->
            <button wire:click="callWaitress" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-500 border border-slate-200 shadow-sm active:scale-95 transition-all hover:bg-slate-50 hover:text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 12.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                </svg>
            </button>
        </div>

        <!-- Category Filters -->
        <div class="flex overflow-x-auto gap-2 px-5 pb-3 pt-1 no-scrollbar scroll-smooth">
            <button wire:click="setCategory('all')" 
                class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-bold transition-all duration-300 border {{ $activeCategory === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-md transform scale-105' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50' }}">
                Semua
            </button>
            @foreach($categories as $category)
                <button wire:click="setCategory({{ $category->id }})" 
                    class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-bold transition-all duration-300 border {{ $activeCategory == $category->id ? 'bg-slate-900 text-white border-slate-900 shadow-md transform scale-105' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Product List -->
    <div class="p-5 space-y-4 min-h-[60vh]">
        @if($products->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 opacity-50">
                <svg class="w-16 h-16 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <p class="text-sm font-bold text-slate-400">Kategori ini kosong</p>
            </div>
        @else
            @foreach($products as $product)
                @php
                    $isSoldOut = $product->stock <= 0;
                    $cartQty = $cart[$product->id] ?? 0;
                    $isMaxStock = $isSoldOut || $cartQty >= $product->stock;
                @endphp

                <div wire:key="product-{{ $product->id }}" class="bg-white p-3 rounded-[1.25rem] shadow-sm border border-slate-100 flex gap-4 transition-all duration-300 {{ $isSoldOut ? 'opacity-60 grayscale' : '' }}">
                    
                    <div class="w-24 h-24 flex-shrink-0 rounded-xl overflow-hidden relative bg-slate-100 shadow-inner">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif

                        @if($isSoldOut)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <span class="text-white text-[10px] font-black uppercase tracking-widest border border-white px-2 py-1 rounded">Habis</span>
                            </div>
                        @elseif($cartQty > 0)
                            <div class="absolute top-1 left-1 bg-indigo-600 text-white text-[10px] font-bold w-6 h-6 flex items-center justify-center rounded-lg shadow-md border-2 border-white">
                                {{ $cartQty }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <h3 class="font-bold text-slate-800 text-[0.95rem] leading-tight line-clamp-1">{{ $product->name }}</h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed line-clamp-2 mt-1">{{ $product->description }}</p>
                        </div>

                        <div class="flex items-end justify-between mt-2">
                            <div>
                                <span class="block font-black text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @if(!$isSoldOut && $product->stock <= 5)
                                    <span class="text-[9px] text-red-500 font-bold animate-pulse">Sisa {{ $product->stock }} porsi</span>
                                @endif
                            </div>

                            @if(!$isSoldOut)
                                @if($cartQty > 0)
                                    <div class="flex items-center bg-slate-900 rounded-lg shadow-lg shadow-slate-900/20 p-1 gap-1">
                                        <button wire:click="removeFromCart({{ $product->id }})" class="w-7 h-7 flex items-center justify-center text-white hover:bg-white/20 rounded transition-colors font-bold text-lg leading-none pb-0.5">-</button>
                                        <span class="text-white text-xs font-bold min-w-[20px] text-center px-1">{{ $cartQty }}</span>
                                        <button wire:click="addToCart({{ $product->id }})" @disabled($isMaxStock) class="w-7 h-7 flex items-center justify-center text-white hover:bg-white/20 rounded transition-colors font-bold text-lg leading-none pb-0.5 disabled:opacity-50 disabled:cursor-not-allowed">+</button>
                                    </div>
                                @else
                                    <button wire:click="addToCart({{ $product->id }})" class="bg-indigo-50 text-indigo-600 border border-indigo-100 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-600 hover:text-white transition-all active:scale-95 shadow-sm">
                                        + Tambah
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Floating Checkout Bar -->
    @if(count($cart) > 0)
    <div class="fixed bottom-0 left-0 w-full px-4 z-[100] pb-4" x-transition>
        <div class="w-full max-w-md mx-auto">
            <button wire:click="openCheckout" class="w-full bg-slate-900 text-white p-3 rounded-2xl shadow-2xl shadow-slate-900/50 flex justify-between items-center group active:scale-[0.98] transition-all border-2 border-slate-700 hover:bg-slate-800">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-600 text-white w-10 h-10 flex items-center justify-center rounded-xl font-black text-sm shadow-inner border-2 border-slate-800">
                        {{ $this->getTotalItems() }}
                    </div>
                    <div class="text-left">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total Harga</span>
                        <span class="font-black text-lg leading-none tracking-tight">Rp {{ number_format($this->getTotalPrice(), 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-white text-slate-900 px-4 py-2 rounded-lg font-bold text-xs flex items-center gap-2 group-active:scale-95 transition-transform shadow-lg">
                    <span>Lanjut Bayar</span> 
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </button>
        </div>
    </div>
    @endif

    <!-- Checkout Modal -->
    @if($isCheckoutOpen)
    <div class="fixed inset-0 z-[110] flex items-end justify-center" x-data="{...livewireModel('isCheckoutOpen')}" x-show="isCheckoutOpen" @keydown.escape.window="isCheckoutOpen = false">
        <!-- Overlay -->
        <div x-show="isCheckoutOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="isCheckoutOpen = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        
        <!-- Modal Content -->
        <div x-show="isCheckoutOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-full"
            class="bg-white w-full max-w-md rounded-t-[2rem] p-5 relative shadow-2xl max-h-[90vh] flex flex-col">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-5 shrink-0"></div>
            
            <div class="flex justify-between items-center mb-5 shrink-0">
                <h2 class="text-xl font-black text-slate-800 tracking-tight">Konfirmasi Pesanan</h2>
                <button @click="isCheckoutOpen = false" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Cart Items -->
            <div class="overflow-y-auto custom-scrollbar flex-1 -mr-2 pr-2 space-y-3">
                @foreach($products->whereIn('id', array_keys($cart)) as $item)
                <div class="flex gap-4 p-3 bg-slate-50 rounded-2xl border border-slate-100 items-center">
                    <div class="bg-white text-indigo-600 w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-xl font-black text-sm border border-slate-100 shadow-sm">
                        {{ $cart[$item->id] }}x
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-800 text-sm line-clamp-1">{{ $item->name }}</h4>
                        <p class="text-xs text-slate-400">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="font-bold text-slate-800 text-sm">
                        Rp {{ number_format($item->price * $cart[$item->id], 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Form & Submission -->
            <div class="space-y-3 pt-5 shrink-0 border-t border-slate-200">
                <div class="flex justify-between items-center pb-2">
                    <span class="font-bold text-slate-500 text-sm">Grand Total</span>
                    <span class="font-black text-indigo-600 text-2xl">Rp {{ number_format($this->getTotalPrice(), 0, ',', '.') }}</span>
                </div>

                <div>
                    <input wire:model.defer="customerName" type="text" placeholder="Nama Pemesan (Wajib)" class="w-full rounded-xl border-slate-200 bg-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 font-bold text-slate-800 py-3 text-sm transition-all shadow-inner placeholder:font-medium placeholder:text-slate-400">
                    @error('customerName') <span class="text-red-500 text-xs font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <textarea wire:model.defer="orderNote" rows="2" placeholder="Catatan untuk pesanan (opsional)" class="w-full rounded-xl border-slate-200 bg-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 text-slate-800 py-3 text-sm resize-none transition-all shadow-inner placeholder:font-medium placeholder:text-slate-400"></textarea>
                
                <button wire:click="submitOrder" wire:loading.attr="disabled" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl shadow-xl shadow-slate-900/30 hover:bg-slate-800 active:scale-95 transition-all disabled:opacity-50 mt-1">
                    <span wire:loading.remove wire:target="submitOrder">🚀 Kirim Pesanan</span>
                    <span wire:loading wire:target="submitOrder">⏳ Memproses...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Success Screen -->
    @if(session()->has('success'))
    <div class="fixed inset-0 z-[120] flex items-center justify-center bg-white/95 backdrop-blur-md px-6" x-data="{ show: true }" x-show="show" x-transition.opacity>
        <div class="text-center w-full max-w-sm">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-3xl font-black text-slate-800 mb-2">Berhasil!</h2>
            <p class="text-slate-500 text-sm mb-8">Pesananmu sudah masuk antrian dapur. Silakan tunggu sebentar ya.</p>
            <button @click="window.location.reload()" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-slate-800 transition-colors shadow-lg">
                Pesan Lagi
            </button>
        </div>
    </div>
    @endif
</div>