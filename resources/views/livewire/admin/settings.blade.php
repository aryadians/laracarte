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
                        <label class="block text-sm font-bold text-slate-700 mb-2">Logo Toko</label>
                        <div class="flex items-center gap-4">
                            @if ($logo && method_exists($logo, 'temporaryUrl'))
                                <img src="{{ $logo->temporaryUrl() }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                            @elseif ($existingLogo)
                                <img src="{{ asset('storage/' . $existingLogo) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <input wire:model="logo" type="file" class="block w-full text-sm text-slate-500 rounded-xl border border-slate-200 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                                <p class="text-[10px] text-slate-400 mt-1">Format: PNG, JPG (Max 1MB). Disarankan persegi (1:1).</p>
                                @error('logo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Restoran / Toko</label>
                        <input wire:model="storeName" type="text" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800 transition-all">
                        @error('storeName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Telepon</label>
                        <input wire:model="storePhone" type="text" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800 transition-all" placeholder="0812...">
                        @error('storePhone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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

                    <div class="pt-4 border-t border-slate-100">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Printer Thermal</label>
                        <input wire:model="printerName" type="text" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800 transition-all" placeholder="Contoh: POS-58">
                        <p class="text-[10px] text-slate-400 mt-1 italic">*Hanya untuk printer yang terhubung ke server/PC kasir.</p>
                        @error('printerName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Midtrans Integration --}}
            <div class="pt-8 border-t border-slate-100">
                <h3 class="font-bold text-slate-700 border-b border-slate-100 pb-2 mb-6 uppercase text-xs tracking-wider">Integrasi Pembayaran Otomatis (Midtrans)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Environment</label>
                        <select wire:model="midtransIsProduction" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800">
                            <option value="0">Sandbox (Testing)</option>
                            <option value="1">Production (Live)</option>
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Client Key</label>
                        <input wire:model="midtransClientKey" type="text" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800" placeholder="SB-Mid-client-...">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Server Key</label>
                        <input wire:model="midtransServerKey" type="password" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800" placeholder="SB-Mid-server-...">
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 mt-3 italic">*Kosongkan jika hanya ingin menggunakan pembayaran manual di kasir.</p>
            </div>

            {{-- Loyalty Program --}}
            <div class="pt-8 border-t border-slate-100">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2 mb-6">
                    <h3 class="font-bold text-slate-700 uppercase text-xs tracking-wider">Program Loyalitas & Member</h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="loyaltyEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 {{ !$loyaltyEnabled ? 'opacity-50 pointer-events-none' : '' }}">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Rasio Poin (Belanja -> 1 Poin)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-500 font-bold text-xs">Rp</span>
                            </div>
                            <input wire:model="pointEarnRate" type="number" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800 transition-all pl-12">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Berapa rupiah belanja untuk mendapatkan 1 poin. Contoh: 10000</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nilai Tukar (1 Poin -> Potongan)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-500 font-bold text-xs">Rp</span>
                            </div>
                            <input wire:model="pointRedeemValue" type="number" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium text-slate-800 transition-all pl-12">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Nilai rupiah per 1 poin saat ditukarkan. Contoh: 100</p>
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