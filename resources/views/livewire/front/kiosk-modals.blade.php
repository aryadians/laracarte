<div>
    {{-- MODAL VARIAN --}}
    @if($isVariantModalOpen && $selectedProduct)
    <div class="fixed inset-0 z-[120] flex items-center justify-center p-4">
        <div wire:click="$set('isVariantModalOpen', false)" class="absolute inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity"></div>
        <div class="bg-white w-full max-w-xl rounded-[3rem] relative shadow-2xl animate-zoom-in max-h-[90vh] flex flex-col overflow-hidden">
            
            <div class="p-8 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <h3 class="font-black text-2xl text-slate-800">{{ $selectedProduct->name }}</h3>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Sesuaikan Pesanan Anda</p>
                </div>
                <button wire:click="$set('isVariantModalOpen', false)" class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar space-y-10">
                @foreach($selectedProduct->variants as $variant)
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-black text-slate-800 text-lg uppercase tracking-tighter">{{ $variant->name }}</h4>
                        @if($variant->is_required) <span class="text-[10px] text-white bg-red-500 px-3 py-1 rounded-full font-black uppercase tracking-widest">Wajib</span> @endif
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($variant->options as $option)
                        <label class="flex flex-col p-5 rounded-[2rem] border-2 transition-all cursor-pointer relative group {{ 
                            ($variant->type == 'radio' && isset($selectedVariants[$variant->id]) && $selectedVariants[$variant->id] == $option->id) ||
                            ($variant->type == 'checkbox' && isset($selectedVariants[$variant->id][$option->id]) && $selectedVariants[$variant->id][$option->id]) 
                            ? 'border-indigo-600 bg-indigo-50 shadow-inner' : 'border-slate-100 bg-white hover:border-slate-200' 
                        }}">
                            @if($variant->type == 'radio')
                                <input type="radio" wire:model.live="selectedVariants.{{ $variant->id }}" value="{{ $option->id }}" class="sr-only">
                            @else
                                <input type="checkbox" wire:model.live="selectedVariants.{{ $variant->id }}.{{ $option->id }}" class="sr-only">
                            @endif
                            
                            <span class="text-base font-black text-slate-800">{{ $option->name }}</span>
                            @if($option->price > 0)
                                <span class="text-sm font-bold text-indigo-600 mt-1">+Rp {{ number_format($option->price, 0, ',', '.') }}</span>
                            @endif

                            <div class="absolute top-4 right-4">
                                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center {{ 
                                    (($variant->type == 'radio' && isset($selectedVariants[$variant->id]) && $selectedVariants[$variant->id] == $option->id) ||
                                    ($variant->type == 'checkbox' && isset($selectedVariants[$variant->id][$option->id]) && $selectedVariants[$variant->id][$option->id])) 
                                    ? 'bg-indigo-600 border-indigo-600' : 'border-slate-200' 
                                }}">
                                    @if((($variant->type == 'radio' && isset($selectedVariants[$variant->id]) && $selectedVariants[$variant->id] == $option->id) ||
                                    ($variant->type == 'checkbox' && isset($selectedVariants[$variant->id][$option->id]) && $selectedVariants[$variant->id][$option->id])))
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
                
                @error('variant_error') <div class="bg-red-50 text-red-600 text-sm font-black p-4 rounded-3xl text-center border border-red-100">{{ $message }}</div> @enderror
            </div>

            <div class="p-8 border-t border-slate-100 bg-white">
                <button wire:click="saveVariantToCart" class="w-full bg-slate-900 hover:bg-black text-white font-black py-5 rounded-[2rem] shadow-2xl flex justify-between px-10 text-xl transition-all active:scale-95">
                    <span>TAMBAH KE PESANAN</span>
                    <span>Rp {{ number_format($currentPrice, 0, ',', '.') }}</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL CHECKOUT KIOSK --}}
    @if($isCheckoutOpen)
    <div class="fixed inset-0 z-[130] flex items-center justify-center p-4">
        <div wire:click="closeCheckout" class="absolute inset-0 bg-slate-900/90 backdrop-blur-xl transition-opacity"></div>
        <div class="bg-white w-full max-w-2xl rounded-[3rem] relative shadow-2xl animate-zoom-in max-h-[90vh] flex flex-col overflow-hidden">
            
            <div class="p-10 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Konfirmasi Pesanan</h2>
                <button wire:click="closeCheckout" class="text-slate-400 hover:text-slate-600"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <div class="p-10 overflow-y-auto custom-scrollbar">
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Nama Anda</label>
                        <input wire:model="customerName" type="text" placeholder="Masukkan nama panggilan..." class="w-full rounded-[1.5rem] border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 font-black text-slate-800 p-6 text-xl transition-all">
                        @error('customerName') <span class="text-red-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-6">
                            <label class="cursor-pointer relative">
                                <input type="radio" wire:model.live="paymentMethod" value="cashier" class="peer sr-only">
                                <div class="p-8 rounded-[2rem] border-4 border-slate-100 bg-white peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all text-center h-full flex flex-col items-center justify-center gap-2">
                                    <span class="text-4xl">💵</span>
                                    <span class="text-lg font-black text-slate-800">BAYAR DI KASIR</span>
                                </div>
                            </label>
                            <label class="cursor-pointer relative">
                                <input type="radio" wire:model.live="paymentMethod" value="midtrans" class="peer sr-only">
                                <div class="p-8 rounded-[2rem] border-4 border-slate-100 bg-white peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all text-center h-full flex flex-col items-center justify-center gap-2">
                                    <span class="text-4xl">💳</span>
                                    <span class="text-lg font-black text-slate-800">BAYAR SEKARANG</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-8 rounded-[2rem] border border-slate-100 space-y-3 mt-8">
                        <div class="flex justify-between items-center text-slate-500 font-bold">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($this->getSubtotal(), 0, ',', '.') }}</span>
                        </div>
                        @if($discountAmount > 0)
                        <div class="flex justify-between items-center text-emerald-600 font-black">
                            <span>Diskon ({{ $appliedPromoName }})</span>
                            <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center text-slate-500 font-bold text-sm">
                            <span>Service Charge ({{ $serviceRate }}%)</span>
                            <span>Rp {{ number_format($this->getServiceCharge(), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500 font-bold text-sm">
                            <span>Pajak ({{ $taxRate }}%)</span>
                            <span>Rp {{ number_format($this->getTaxAmount(), 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-4 mt-2 flex justify-between items-center">
                            <span class="font-black text-slate-800 text-xl">TOTAL BAYAR</span>
                            <span class="font-black text-indigo-600 text-3xl">Rp {{ number_format($this->getGrandTotal(), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-10 border-t border-slate-100 bg-white">
                <button wire:click="submitOrder" wire:loading.attr="disabled" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-6 rounded-[2rem] shadow-2xl flex justify-center items-center gap-4 text-2xl transition-all active:scale-95">
                    <span wire:loading.remove>🔥 PROSES PESANAN SEKARANG</span>
                    <span wire:loading>SEDANG MEMPROSES...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- SUCCESS MODAL KIOSK --}}
    @if(session()->has('success'))
    <div class="fixed inset-0 z-[200] flex items-center justify-center bg-indigo-600 px-6 animate-fade-in">
        <div class="text-center text-white max-w-lg">
            <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-5xl font-black mb-4 uppercase tracking-tighter">BERHASIL!</h2>
            <p class="text-xl font-bold opacity-80 mb-12">{{ session('success') }}</p>
            <button onclick="window.location.reload()" class="w-full bg-white text-indigo-600 py-6 rounded-[2rem] font-black text-2xl hover:bg-slate-100 shadow-2xl transition-all active:scale-95">SELESAI</button>
        </div>
    </div>
    @endif
</div>
