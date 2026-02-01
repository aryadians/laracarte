<div class="p-6">
    <div class="flex items-center gap-4 mb-6">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
        @else
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center text-slate-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif
        <div>
            <h2 class="text-xl font-black text-slate-800">{{ $product->name }}</h2>
            <p class="text-sm text-slate-500">Kelola varian seperti topping, ukuran, atau level pedas.</p>
        </div>
    </div>

    {{-- Form Tambah Varian --}}
    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-8">
        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Buat Grup Varian Baru</h4>
        <div class="flex gap-3 items-end">
            <div class="flex-1">
                <input wire:model="newVariantName" type="text" placeholder="Nama Varian (Contoh: Level Pedas)" class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="w-32">
                <select wire:model="newVariantType" class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="radio">Pilih 1 (Radio)</option>
                    <option value="checkbox">Pilih Banyak (Check)</option>
                </select>
            </div>
            <div class="flex items-center h-10 px-2">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="newVariantRequired" class="sr-only peer">
                    <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                    <span class="ms-2 text-xs font-bold text-gray-500">Wajib?</span>
                </label>
            </div>
            <button wire:click="addVariant" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-black transition-colors">
                + Tambah
            </button>
        </div>
        @error('newVariantName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    {{-- List Varian --}}
    <div class="space-y-4">
        @foreach($variants as $variant)
        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="bg-white p-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-[10px] font-bold uppercase">{{ $variant->type }}</span>
                    <h3 class="font-bold text-slate-800">{{ $variant->name }}</h3>
                    @if($variant->is_required)
                        <span class="text-[10px] text-red-500 font-bold bg-red-50 px-2 py-0.5 rounded-full border border-red-100">Wajib Pilih</span>
                    @endif
                </div>
                <div class="flex gap-2">
                    <button wire:click="setActiveVariant({{ $variant->id }})" class="text-xs font-bold text-indigo-600 hover:underline bg-indigo-50 px-3 py-1.5 rounded-lg">
                        {{ $activeVariantId == $variant->id ? 'Tutup Opsi' : 'Kelola Opsi (' . $variant->options->count() . ')' }}
                    </button>
                    <button wire:click="deleteVariant({{ $variant->id }})" class="text-slate-400 hover:text-red-500 p-1.5 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>

            {{-- Opsi Manager (Accordion) --}}
            @if($activeVariantId == $variant->id)
            <div class="bg-slate-50 p-4 border-t border-slate-100">
                
                {{-- Form Tambah Opsi --}}
                <div class="flex gap-2 mb-4">
                    <input wire:model="newOptionName" type="text" placeholder="Nama Opsi (Misal: Pedas)" class="flex-1 rounded-lg border-slate-300 text-xs">
                    <div class="relative w-32">
                        <input wire:model="newOptionPrice" type="number" placeholder="0" class="w-full rounded-lg border-slate-300 text-xs pl-8">
                        <span class="absolute left-3 top-2 text-xs text-slate-400">Rp</span>
                    </div>
                    <button wire:click="addOption({{ $variant->id }})" class="bg-indigo-600 text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-indigo-700">Simpan</button>
                </div>

                {{-- List Opsi --}}
                <ul class="space-y-2">
                    @foreach($variant->options as $option)
                    <li class="flex justify-between items-center bg-white p-2 px-3 rounded-lg border border-slate-200 shadow-sm">
                        <span class="text-sm font-bold text-slate-700">{{ $option->name }}</span>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-500 font-mono">+ Rp {{ number_format($option->price, 0, ',', '.') }}</span>
                            <button wire:click="deleteOption({{ $option->id }})" class="text-red-400 hover:text-red-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>