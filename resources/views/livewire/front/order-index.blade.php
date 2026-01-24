<div>
    <div class="bg-white sticky top-0 z-40 shadow-sm/50 border-b border-slate-50">
        <div class="px-6 py-4 flex justify-between items-center bg-white">
            <div>
                <p class="text-[0.65rem] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Self Order di</p>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">Lara<span class="text-indigo-600">Carte.</span></h1>
            </div>
            <div class="bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-xl text-xs font-bold border border-indigo-100 flex items-center shadow-sm">
                🍽️ <span class="ml-1">{{ $table_name }}</span>
            </div>
        </div>

        <div class="flex overflow-x-auto gap-3 px-6 pb-4 pt-1 no-scrollbar">
            <button wire:click="setCategory('all')" 
                class="flex-shrink-0 px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 {{ $activeCategory === 'all' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 scale-105' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                🔥 Semua
            </button>

            @foreach($categories as $category)
                <button wire:click="setCategory({{ $category->id }})" 
                    class="flex-shrink-0 px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 {{ $activeCategory == $category->id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 scale-105' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                    {{ $category->icon }} {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="p-6 pb-32 bg-slate-50 min-h-screen">
        @if($products->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-slate-400">
                <svg class="w-16 h-16 mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <p class="font-bold">Belum ada menu di kategori ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4">
                @foreach($products as $product)
                <div class="flex gap-4 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-24 h-24 flex-shrink-0 bg-slate-100 rounded-xl overflow-hidden relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-50">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <h3 class="font-bold text-slate-800 line-clamp-1 text-[0.95rem]">{{ $product->name }}</h3>
                            <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">{{ $product->description }}</p>
                        </div>
                        
                        <div class="flex justify-between items-end mt-2">
                            <span class="font-black text-slate-800 text-[0.95rem]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            
                            @if(isset($cart[$product->id]))
                                <div class="flex items-center bg-slate-900 rounded-lg p-1 shadow-md">
                                    <button wire:click="removeFromCart({{ $product->id }})" class="w-7 h-7 text-white flex items-center justify-center font-bold hover:bg-slate-700 rounded transition-colors">-</button>
                                    <span class="w-8 text-center text-white text-xs font-bold">{{ $cart[$product->id] }}</span>
                                    <button wire:click="addToCart({{ $product->id }})" class="w-7 h-7 text-white flex items-center justify-center font-bold hover:bg-slate-700 rounded transition-colors">+</button>
                                </div>
                            @else
                                <button wire:click="addToCart({{ $product->id }})" class="bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-100">
                                    + Pesan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    @if($this->getTotalItems() > 0)
    <div class="fixed bottom-0 left-0 w-full p-4 z-40 bg-gradient-to-t from-white via-white to-transparent pb-6">
        <div class="max-w-md mx-auto">
            <button wire:click="checkout" class="w-full bg-slate-900 text-white rounded-2xl p-4 shadow-2xl shadow-indigo-900/40 flex justify-between items-center transform active:scale-95 transition-all duration-300 border border-slate-700">
                <div class="flex flex-col text-left pl-2">
                    <span class="text-[0.65rem] text-slate-400 font-bold uppercase tracking-wider">{{ $this->getTotalItems() }} Item dipilih</span>
                    <span class="text-lg font-black tracking-tight">Rp {{ number_format($this->getTotalPrice(), 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center font-bold text-indigo-400 text-sm pr-2">
                    Lihat Keranjang
                    <svg class="w-5 h-5 ml-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </button>
        </div>
    </div>
    @endif

    @if($isCheckoutOpen)
    <div class="fixed inset-0 z-[60] flex items-end justify-center">
        <div wire:click="closeCheckout" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="bg-white w-full max-w-md rounded-t-[2rem] p-6 relative shadow-2xl transform transition-transform duration-300 animate-slide-up">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-black text-slate-800">Konfirmasi Pesanan</h2>
                <button wire:click="closeCheckout" class="p-2 bg-slate-100 rounded-full text-slate-500 hover:bg-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="max-h-60 overflow-y-auto space-y-3 mb-6 pr-2 -mr-2 custom-scrollbar">
                @foreach($products->whereIn('id', array_keys($cart)) as $item)
                <div class="flex justify-between items-center border-b border-slate-50 pb-3 last:border-0">
                    <div class="flex items-center gap-4">
                        <div class="bg-indigo-50 text-indigo-700 w-8 h-8 flex items-center justify-center rounded-lg font-bold text-sm">
                            {{ $cart[$item->id] }}x
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm line-clamp-1">{{ $item->name }}</p>
                            <p class="text-xs text-slate-400">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="font-bold text-slate-800 text-sm">
                        Rp {{ number_format($item->price * $cart[$item->id], 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-slate-50 p-5 rounded-2xl space-y-4">
                <div class="flex justify-between items-center border-b border-slate-200 pb-4">
                    <span class="font-bold text-slate-500 text-sm">Total Pembayaran</span>
                    <span class="font-black text-indigo-600 text-2xl">Rp {{ number_format($this->getTotalPrice(), 0, ',', '.') }}</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Pemesan <span class="text-red-500">*</span></label>
                    <input wire:model="customerName" type="text" placeholder="Masukkan nama kamu..." class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 font-bold text-slate-700 py-3 bg-white shadow-sm">
                    @error('customerName') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Catatan (Opsional)</label>
                    <textarea wire:model="orderNote" rows="2" placeholder="Contoh: Jangan pedas, minta sendok..." class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 text-slate-700 py-3 bg-white shadow-sm resize-none"></textarea>
                </div>

                <button wire:click="submitOrder" wire:loading.attr="disabled" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-500/20 hover:bg-slate-800 active:scale-95 transition-all flex justify-center items-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove>🚀 Kirim Pesanan ke Dapur</span>
                    <span wire:loading>⏳ Sedang Mengirim...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if(session()->has('success'))
    <div class="fixed inset-0 z-[70] flex items-center justify-center bg-white animate-fade-in p-6">
        <div class="text-center w-full max-w-sm">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-3xl font-black text-slate-800 mb-3 tracking-tight">Pesanan Diterima!</h2>
            <p class="text-slate-500 mb-8 leading-relaxed">Terima kasih <span class="font-bold text-slate-800">{{ $customerName }}</span>! Pesananmu sudah masuk ke sistem dapur kami. Mohon tunggu sebentar ya.</p>
            
            <button onclick="window.location.reload()" class="w-full bg-slate-100 text-slate-700 py-4 rounded-xl font-bold hover:bg-slate-200 transition-colors">
                Pesan Menu Lainnya
            </button>
        </div>
    </div>
    @endif
</div>