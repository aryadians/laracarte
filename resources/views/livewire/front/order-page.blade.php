<div class="relative min-h-screen pb-32" x-data>

    @if(session()->has('success_waitress'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-24 left-1/2 -translate-x-1/2 z-[150] w-max max-w-[90%] px-4">
        <div class="flex items-center gap-3 px-4 py-3 bg-slate-900/95 backdrop-blur-md text-white rounded-full shadow-2xl border border-slate-700">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            <span class="text-xs font-bold">{{ session('success_waitress') }}</span>
        </div>
    </div>
    @endif

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

            <button wire:click="callWaitress" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-500 border border-slate-200 shadow-sm active:scale-95 transition-all hover:bg-slate-50 hover:text-red-500 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:animate-swing" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 12.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                </svg>
            </button>
        </div>

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

    <div class="p-5 space-y-4 min-h-[60vh]">
        @if($products->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 opacity-50">
                <svg class="w-16 h-16 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <p class="text-sm font-bold text-slate-400">Kategori ini kosong</p>
            </div>
        @else
            @foreach($products as $product)
                @php
                    $isSoldOut = $product->stock <= 0 || !$product->is_available;
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
                                    <div class="flex items-center bg-slate-900 rounded-lg shadow-lg shadow-slate-900/20 p-1 gap-3">
                                        <button wire:click="removeFromCart({{ $product->id }})" class="w-6 h-6 flex items-center justify-center text-white hover:bg-white/20 rounded transition-colors font-bold text-lg leading-none pb-0.5">-</button>
                                        <span class="text-white text-xs font-bold min-w-[10px] text-center">{{ $cartQty }}</span>
                                        <button wire:click="addToCart({{ $product->id }})" @disabled($isMaxStock) class="w-6 h-6 flex items-center justify-center text-white hover:bg-white/20 rounded transition-colors font-bold text-lg leading-none pb-0.5 disabled:opacity-50">+</button>
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

    @if(count($cart) > 0)
    <div class="fixed bottom-0 left-0 w-full px-5 pb-6 z-[100] pointer-events-none flex justify-center">
        <div class="w-full max-w-md pointer-events-auto">
            <button wire:click="openCheckout" class="w-full bg-slate-900 text-white p-3.5 rounded-2xl shadow-2xl shadow-slate-900/40 flex justify-between items-center group active:scale-[0.98] transition-all border border-slate-700 cursor-pointer hover:bg-slate-800">
                <div class="flex items-center gap-3 pl-1">
                    <div class="bg-indigo-500 text-white w-10 h-10 flex items-center justify-center rounded-full font-black text-sm shadow-inner border-2 border-slate-800 animate-bounce">
                        {{ $this->getTotalItems() }}
                    </div>
                    <div class="text-left flex flex-col">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total Bayar</span>
                        <span class="font-black text-lg leading-none tracking-tight">Rp {{ number_format($this->getTotalPrice(), 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="bg-white text-slate-900 px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 group-active:scale-95 transition-transform shadow-lg">
                    Lanjut Bayar <svg class="w-4 h-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </button>
        </div>
    </div>
    @endif

    @if($isCheckoutOpen)
    <div class="fixed inset-0 z-[110] flex items-end justify-center px-2 pb-2">
        <div wire:click="closeCheckout" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        
        <div class="bg-white w-full max-w-md rounded-[2rem] p-6 relative shadow-2xl animate-slide-up max-h-[85vh] flex flex-col">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6 shrink-0"></div>
            
            <div class="flex justify-between items-center mb-6 shrink-0">
                <h2 class="text-xl font-black text-slate-800 tracking-tight">Konfirmasi Pesanan</h2>
                <button wire:click="closeCheckout" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="overflow-y-auto custom-scrollbar flex-1 mb-6 -mr-2 pr-2 space-y-3">
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

            <div class="space-y-3 shrink-0 pt-4 border-t border-slate-100">
                <div class="flex justify-between items-center pb-2">
                    <span class="font-bold text-slate-500 text-sm">Grand Total</span>
                    <span class="font-black text-indigo-600 text-2xl">Rp {{ number_format($this->getTotalPrice(), 0, ',', '.') }}</span>
                </div>

                <div>
                    <input wire:model.blur="customerName" type="text" placeholder="Nama Pemesan (Wajib)" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 font-bold text-slate-800 py-3.5 text-sm transition-all shadow-inner">
                    @error('customerName') <span class="text-red-500 text-xs font-bold ml-1 block">{{ $message }}</span> @enderror
                </div>
                
                <textarea wire:model.blur="orderNote" rows="2" placeholder="Catatan (Opsional)" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 text-slate-800 py-3.5 text-sm resize-none transition-all shadow-inner"></textarea>
                
                @error('checkout_error')
                    <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-bold">Terjadi Kesalahan:</p>
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @enderror

                <button wire:click="submitOrder" wire:loading.attr="disabled" wire:target="submitOrder" class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl shadow-xl shadow-slate-900/30 hover:bg-slate-800 active:scale-95 transition-all disabled:opacity-50 mt-2 flex justify-center items-center gap-2">
                    <span wire:loading.remove wire:target="submitOrder">🚀 Kirim Pesanan</span>
                    <span wire:loading wire:target="submitOrder" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if(session()->has('success'))
    <div class="fixed inset-0 z-[120] flex items-center justify-center bg-white/95 backdrop-blur-md px-6 animate-fade-in">
        <div class="text-center w-full max-w-sm">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-3xl font-black text-slate-800 mb-2">Berhasil!</h2>
            <p class="text-slate-500 text-sm mb-8">Pesananmu sudah masuk antrian dapur.</p>
            <button onclick="window.location.reload()" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-slate-800 transition-colors shadow-lg">
                Pesan Lagi
            </button>
        </div>
    </div>
    @endif
</div>