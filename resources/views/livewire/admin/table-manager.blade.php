<div class="p-6">
    <div class="flex flex-col md:flex-row gap-8 items-start mb-10">
        <div class="flex-1">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Meja 🪑</h2>
            <p class="text-slate-500 mt-2">Buat meja baru dan cetak QR Code untuk ditempel.</p>
        </div>

        <div class="w-full md:w-96 bg-white p-6 rounded-3xl shadow-xl shadow-indigo-100 border border-indigo-50">
            <form wire:submit.prevent="store" class="flex flex-col gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Meja Baru</label>
                    <input wire:model="name" type="text" placeholder="Contoh: Meja 10" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 font-bold text-slate-700">
                    @error('name') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="bg-slate-900 text-white font-bold py-3 rounded-xl hover:bg-slate-800 transition-all flex justify-center items-center gap-2">
                    <span>+ Tambah Meja</span>
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($tables as $table)
        <div class="group bg-white rounded-3xl p-5 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-bl-full -mr-4 -mt-4 opacity-50 group-hover:scale-110 transition-transform"></div>

            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg text-xs font-bold border border-indigo-100">
                    {{ $table->name }}
                </span>
                <button wire:click="delete({{ $table->id }})" wire:confirm="Hapus meja ini?" class="text-slate-300 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>

            <div class="flex flex-col items-center justify-center py-4">
                <div class="bg-white p-2 rounded-xl border-2 border-dashed border-slate-200 group-hover:border-indigo-400 transition-colors">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url('/order/' . $table->slug) }}" 
                         alt="QR Meja" 
                         class="w-32 h-32 object-contain rounded-lg">
                </div>
                
                <p class="text-[0.65rem] text-slate-400 mt-3 font-mono text-center break-all px-2">
                    {{ url('/order/' . $table->slug) }}
                </p>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2">
                <a href="{{ url('/order/' . $table->slug) }}" target="_blank" class="text-center py-2 bg-slate-50 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-100 transition-colors">
                    🔗 Tes Link
                </a>
                <button onclick="window.print()" class="text-center py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30">
                    🖨️ Print
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>