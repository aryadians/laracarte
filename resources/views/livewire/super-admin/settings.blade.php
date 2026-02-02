<div class="max-w-4xl mx-auto space-y-8">
    {{-- Status Message --}}
    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-bold text-sm">{{ session('message') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-rose-500"></div>
        
        <form wire:submit="save" class="p-8 md:p-12 space-y-10">
            {{-- General Section --}}
            <section class="space-y-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h4 class="text-lg font-black text-slate-800 uppercase tracking-tight">Identitas Platform</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2.5">Nama Aplikasi</label>
                        <input wire:model="appName" type="text" class="w-full bg-slate-50 border-slate-200 rounded-2xl px-5 py-4 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-slate-700 transition-all shadow-sm" placeholder="Contoh: LaraCarte">
                        @error('appName') <span class="text-rose-500 text-xs font-bold mt-2 inline-block italic">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2.5">Email Support</label>
                        <input wire:model="supportEmail" type="email" class="w-full bg-slate-50 border-slate-200 rounded-2xl px-5 py-4 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-slate-700 transition-all shadow-sm" placeholder="support@domain.com">
                        @error('supportEmail') <span class="text-rose-500 text-xs font-bold mt-2 inline-block italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2.5">Teks Footer (Global)</label>
                        <input wire:model="footerText" type="text" class="w-full bg-slate-50 border-slate-200 rounded-2xl px-5 py-4 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-slate-700 transition-all shadow-sm" placeholder="© 2026 Developer Name">
                        @error('footerText') <span class="text-rose-500 text-xs font-bold mt-2 inline-block italic">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Maintenance Section --}}
            <section class="space-y-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-slate-800 uppercase tracking-tight leading-none">Status Platform</h4>
                        <p class="text-xs text-slate-400 font-bold mt-1.5">Kontrol akses publik ke semua restoran.</p>
                    </div>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <h5 class="text-sm font-black text-slate-700">Maintenance Mode (Mode Pemeliharaan)</h5>
                        <p class="text-xs text-slate-500 font-medium mt-1">Jika aktif, tenant tidak akan bisa memproses pesanan baru.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="maintenanceMode" class="sr-only peer">
                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-rose-600"></div>
                    </label>
                </div>
            </section>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end pt-8 gap-4 border-t border-slate-100">
                <button type="button" onclick="window.location.reload()" class="px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black rounded-2xl text-xs uppercase tracking-widest transition-all">Reset</button>
                <button type="submit" class="px-12 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest transition-all shadow-xl shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:-translate-y-1 flex items-center gap-3">
                    <span wire:loading.remove>Simpan Perubahan</span>
                    <span wire:loading class="flex items-center gap-3">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
