<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100">
            <h2 class="text-xl font-black text-slate-800">Konfigurasi Toko</h2>
            <p class="text-slate-500 text-sm mt-1">Atur identitas toko dan biaya tambahan (Pajak/Service).</p>
        </div>
        
        <form wire:submit="updateSettings" class="p-8 space-y-8">
            {{-- Notifikasi Sukses --}}
            @if (session()->has('message'))
                <div class="bg-green-50 text-green-600 px-4 py-3 rounded-xl border border-green-100 flex items-center gap-3 animate-fade-in-down">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold text-sm">{{ session('message') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Identitas Toko --}}
                <div class="space-y-6">
                    <h3 class="font-bold text-slate-700 border-b border-slate-100 pb-2 mb-4 uppercase text-xs tracking-wider">Identitas</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Restoran / Toko</label>
                        <input wire:model="storeName" type="text" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800 transition-all">
                        @error('storeName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat (Untuk Struk)</label>
                        <textarea wire:model="storeAddress" rows="3" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800 transition-all"></textarea>
                        @error('storeAddress') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Pajak & Biaya --}}
                <div class="space-y-6">
                    <h3 class="font-bold text-slate-700 border-b border-slate-100 pb-2 mb-4 uppercase text-xs tracking-wider">Pajak & Biaya</h3>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pajak PPN / PB1 (%)</label>
                        <div class="relative">
                            <input wire:model="taxRate" type="number" step="0.1" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800 transition-all pr-12">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-slate-500 font-bold">%</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Default: 11% (Isi 0 jika tanpa pajak)</p>
                        @error('taxRate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Service Charge (%)</label>
                        <div class="relative">
                            <input wire:model="serviceCharge" type="number" step="0.1" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800 transition-all pr-12">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-slate-500 font-bold">%</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Default: 5% (Isi 0 jika gratis layanan)</p>
                        @error('serviceCharge') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex items-center gap-2">
                    <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <svg wire:loading class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>