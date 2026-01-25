<div wire:poll.30s> <div class="bg-slate-900 rounded-3xl p-8 mb-8 relative overflow-hidden shadow-2xl shadow-indigo-500/20">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-16 -mt-16 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -ml-16 -mb-16 animate-blob animation-delay-2000"></div>
        
        <div class="relative z-10">
            <h2 class="text-3xl font-black text-white mb-2">Halo, Admin LaraCarte! 👋</h2>
            <p class="text-slate-400 text-lg">Berikut adalah ringkasan performa restoranmu hari ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center font-bold text-xl">
                    💰
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Pendapatan Hari Ini</p>
                    <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-green-500 h-full rounded-full" style="width: 45%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-bold text-xl">
                    🛍️
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Order Hari Ini</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $todayOrders }} Pesanan</h3>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-indigo-500 h-full rounded-full" style="width: 60%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center font-bold text-xl">
                    🍽️
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Pesanan Aktif (Dapur)</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $activeOrders }} Meja</h3>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-orange-500 h-full rounded-full" style="width: {{ $activeOrders * 10 }}%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Transaksi Terkini</h3>
            <a href="{{ route('admin.orders') }}" class="text-indigo-600 text-sm font-bold hover:underline">Lihat Semua &rarr;</a>
        </div>
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Waktu</th>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3">Meja</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($recentOrders as $order)
                <tr>
                    <td class="px-6 py-4 text-slate-400 text-xs">{{ $order->created_at->diffForHumans() }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4">{{ $order->table->name }}</td>
                    <td class="px-6 py-4 font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold uppercase
                            {{ $order->status == 'paid' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $order->status == 'cooking' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $order->status == 'served' ? 'bg-orange-100 text-orange-700' : '' }}
                        ">
                            {{ $order->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada transaksi hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>