<div class="space-y-8">
    {{-- Global Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-200 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-indigo-100 text-xs font-black uppercase tracking-widest mb-2">Omzet Global</p>
                <h3 class="text-4xl font-black italic">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <div class="mt-4 flex items-center gap-2 text-indigo-100 text-[10px] font-bold">
                    <span class="px-2 py-0.5 bg-white/20 rounded-full lowercase italic">platform wide</span>
                </div>
            </div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Total Pesanan</p>
                <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($totalOrders) }}</h3>
            </div>
            <div class="mt-4 flex items-center gap-4">
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 rounded-full" style="width: 75%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Tenant Aktif</p>
                <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ $activeTenants }}</h3>
            </div>
            <div class="mt-4">
                <span class="text-emerald-500 text-xs font-black flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Berjalan Normal
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Revenue History --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50">
                <h4 class="font-black text-slate-800 uppercase tracking-tight text-sm">Pertumbuhan Omzet Bulanan</h4>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($monthlyStats as $stat)
                        <div class="group">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-slate-600 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $stat['label'] }}</span>
                                <span class="text-sm font-black text-slate-800">Rp {{ number_format($stat['total'], 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full h-3 bg-slate-50 rounded-full overflow-hidden border border-slate-100 shadow-inner">
                                <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full transition-all duration-1000 group-hover:from-indigo-600 group-hover:to-purple-700" style="width: {{ $totalRevenue > 0 ? ($stat['total'] / $totalRevenue * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Top Performers --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50">
                <h4 class="font-black text-slate-800 uppercase tracking-tight text-sm">Top Restoran by Revenue</h4>
            </div>
            <div class="p-0">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Restoran</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Penghasilan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($topRestaurants as $index => $item)
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-all">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 tracking-tight leading-none group-hover:text-indigo-700 transition-colors">{{ $item->tenant->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase italic tracking-wider">{{ $item->tenant->address ? Str::limit($item->tenant->address, 30) : 'LOKASI TIDAK TERIDENTIFIKASI' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="inline-block px-4 py-2 bg-emerald-50 text-emerald-700 text-xs font-black rounded-xl border border-emerald-100/50 group-hover:bg-emerald-500 group-hover:text-white group-hover:scale-105 transition-all">
                                    Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
