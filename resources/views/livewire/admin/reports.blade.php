<div class="space-y-6">
    {{-- Header & Filter --}}
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div>
            <h2 class="text-xl font-black text-slate-800">Laporan Penjualan</h2>
            <p class="text-slate-500 text-sm">Analisis performa & export data.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-xl border border-slate-200">
                <input wire:model.live="startDate" type="date" class="bg-transparent border-none text-xs font-bold text-slate-600 focus:ring-0 p-0">
                <span class="text-slate-400">-</span>
                <input wire:model.live="endDate" type="date" class="bg-transparent border-none text-xs font-bold text-slate-600 focus:ring-0 p-0">
            </div>

            <button wire:click="exportExcel" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-green-700 shadow-lg shadow-green-500/20 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Excel
            </button>
            
            <button wire:click="exportPdf" class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-700 shadow-lg shadow-red-500/20 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                PDF
            </button>
        </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Omzet</p>
            <h3 class="text-2xl font-black text-indigo-600">Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Transaksi</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $summary['total_orders'] ?? 0 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Rata-rata Order</p>
            <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($summary['avg_order'] ?? 0, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-slate-800 mb-4">Produk Terlaris</h3>
        <div class="space-y-4">
            @forelse($topProducts as $item)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-xs font-bold">
                        {{ $loop->iteration }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700">{{ $item->product->name }}</p>
                        <p class="text-[10px] text-slate-400">{{ $item->total_qty }} terjual</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-600">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</span>
            </div>
            @empty
            <p class="text-center text-slate-400 text-sm py-4">Belum ada data penjualan.</p>
            @endforelse
        </div>
    </div>
</div>
