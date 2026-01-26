<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-6 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-indigo-100 text-sm font-bold uppercase tracking-widest mb-1">Total Omzet</p>
                <h3 class="text-3xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p class="text-xs text-indigo-200 mt-2 font-medium">Pada tanggal {{ \Carbon\Carbon::parse($dateFilter)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-2xl"></div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-lg relative overflow-hidden">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Transaksi Sukses</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $totalTransactions }} <span class="text-sm font-medium text-slate-400">Pesanan</span></h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-lg flex flex-col justify-center">
            <label class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Filter Tanggal</label>
            <input type="date" wire:model.live="dateFilter" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Daftar Pesanan Lunas</h3>
            
            <button wire:click="exportCsv" wire:loading.attr="disabled" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all active:scale-95 shadow-md shadow-green-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span wire:loading.remove wire:target="exportCsv">Export CSV</span>
                <span wire:loading wire:target="exportCsv">Proses...</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">ID Pesanan</th>
                        <th class="px-6 py-4">Waktu (WIB)</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Meja</th>
                        <th class="px-6 py-4 text-right">Total Bayar</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-slate-400">#{{ $order->id }}</td>
                        <td class="px-6 py-4 font-bold">{{ $order->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded-lg text-xs font-bold">{{ $order->table->name ?? 'Takeaway' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                                LUNAS
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="font-bold">Tidak ada transaksi pada tanggal ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>