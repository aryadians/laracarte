<div class="space-y-6">
    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Member</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $customers->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Poin Beredar</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ number_format(\App\Models\Customer::sum('points_balance'), 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Tingkat Retensi</p>
                    <h3 class="text-2xl font-black text-slate-800">85%</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-black text-slate-800 tracking-tight">Daftar Member</h2>
                <p class="text-xs text-slate-500 font-medium">Kelola data pelanggan dan pantau aktivitas loyalitas mereka.</p>
            </div>
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau nomor HP..." class="w-full md:w-80 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 font-medium text-sm py-2.5 pl-10 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Member</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontak</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Poin Aktif</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kunjungan Terakhir</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-xs">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $customer->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium italic">Gabung pada {{ $customer->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-600">{{ $customer->phone_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-xs font-black rounded-lg border border-amber-100">
                                    {{ number_format($customer->points_balance, 0, ',', '.') }} Poin
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-slate-500">{{ $customer->last_visit ? $customer->last_visit->diffForHumans() : '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="showHistory({{ $customer->id }})" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-all">
                                Riwayat Poin
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800">Tidak ada member ditemukan</h3>
                                <p class="text-xs text-slate-400 mt-1">Gunakan kata kunci pencarian lain atau buat member baru lewat pesanan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-slate-100">
            {{ $customers->links() }}
        </div>
    </div>

    {{-- History Modal --}}
    @if($showHistoryModal && $selectedCustomer)
    <div class="fixed inset-0 z-[100] flex items-center justify-center px-4">
        <div wire:click="$set('showHistoryModal', false)" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div class="bg-white w-full max-w-2xl rounded-[2.5rem] relative shadow-2xl animate-scale-in flex flex-col overflow-hidden max-h-[85vh]">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black">
                        {{ substr($selectedCustomer->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">{{ $selectedCustomer->name }}</h3>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">{{ $selectedCustomer->phone_number }}</p>
                    </div>
                </div>
                <button wire:click="$set('showHistoryModal', false)" class="text-slate-400 hover:text-slate-600 bg-white shadow-sm border border-slate-100 p-2 rounded-full transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar">
                <div class="space-y-4">
                    @forelse($selectedCustomer->pointTransactions as $tx)
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 {{ $tx->type == 'earn' ? 'bg-emerald-50/30' : 'bg-red-50/30' }}">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $tx->type == 'earn' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                @if($tx->type == 'earn')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $tx->description }}</p>
                                <p class="text-[10px] text-slate-500 font-medium capitalize">{{ $tx->created_at->format('d M Y, H:i') }} • {{ $tx->type == 'earn' ? 'Pemasukan' : 'Pengurangan' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black {{ $tx->type == 'earn' ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $tx->type == 'earn' ? '+' : '-' }} {{ number_format($tx->points, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 text-center">
                        <p class="text-sm text-slate-400 font-bold">Belum ada riwayat transaksi poin.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 flex justify-center bg-slate-50/30">
                <span class="text-xs font-bold text-slate-500 italic">Loyalty Program LaraCarte • Real-time tracking</span>
            </div>
        </div>
    </div>
    @endif
</div>
