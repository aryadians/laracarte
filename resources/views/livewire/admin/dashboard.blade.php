<div wire:poll.10s> @if($waitressCalls->isNotEmpty())
        <div class="mb-6 space-y-4 animate-bounce-in">
            @foreach($waitressCalls as $call)
                <div class="bg-red-500 text-white p-4 rounded-2xl shadow-lg shadow-red-500/40 flex justify-between items-center border-l-8 border-red-700">
                    <div class="flex items-center gap-4">
                        <div class="bg-white/20 p-3 rounded-full animate-pulse">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-xl">PANGGILAN DARI {{ strtoupper($call->table->name) }}!</h3>
                            <p class="text-red-100 text-sm">Dipanggil {{ $call->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <button wire:click="markAsSolved({{ $call->id }})" class="bg-white text-red-600 px-6 py-2 rounded-xl font-bold hover:bg-red-50 transition-colors shadow-sm">
                        ✅ Sudah Didatangi
                    </button>
                </div>
            @endforeach
            
            <audio id="notifSound" autoplay>
                <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
            </audio>
            
            <script>
                // Script memaksa play sound saat elemen ini muncul di DOM
                var audio = document.getElementById("notifSound");
                if(audio) {
                    audio.play().catch(error => {
                        console.log("Autoplay blocked by browser. User interaction needed.");
                    });
                }
            </script>
        </div>
    @endif

    <div class="bg-slate-900 rounded-3xl p-8 mb-8 relative overflow-hidden shadow-2xl shadow-indigo-500/20">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-16 -mt-16 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -ml-16 -mb-16 animate-blob animation-delay-2000"></div>
        
        <div class="relative z-10">
            <h2 class="text-3xl font-black text-white mb-2">Halo, Admin LaraCarte! 👋</h2>
            <p class="text-slate-400 text-lg">Berikut adalah ringkasan performa restoranmu hari ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        @php
            $targetRevenue = 2000000; 
            $revenuePercent = $todayRevenue > 0 ? min(($todayRevenue / $targetRevenue) * 100, 100) : 0;
        @endphp
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center font-bold text-xl">💰</div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Pendapatan Hari Ini</p>
                    <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mb-1">
                <div class="bg-green-500 h-full rounded-full transition-all duration-1000" style="width: {{ $revenuePercent }}%"></div>
            </div>
            <p class="text-[10px] text-slate-400">Target: Rp {{ number_format($targetRevenue, 0, ',', '.') }}</p>
        </div>

        @php
            $targetOrder = 50; 
            $orderPercent = $todayOrders > 0 ? min(($todayOrders / $targetOrder) * 100, 100) : 0;
        @endphp
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-bold text-xl">🛍️</div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Order Hari Ini</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $todayOrders }} Pesanan</h3>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mb-1">
                <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000" style="width: {{ $orderPercent }}%"></div>
            </div>
            <p class="text-[10px] text-slate-400">Target: {{ $targetOrder }} Pesanan</p>
        </div>

        @php
            $kitchenCapacity = 20;
            $kitchenPercent = $activeOrders > 0 ? min(($activeOrders / $kitchenCapacity) * 100, 100) : 0;
        @endphp
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center font-bold text-xl">🍽️</div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Pesanan Aktif (Dapur)</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $activeOrders }} Meja</h3>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mb-1">
                <div class="bg-orange-500 h-full rounded-full transition-all duration-1000" style="width: {{ $kitchenPercent }}%"></div>
            </div>
            <p class="text-[10px] text-slate-400">Kapasitas Dapur: {{ $kitchenPercent }}%</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Transaksi Terkini</h3>
            <a href="{{ route('admin.orders') }}" class="text-indigo-600 text-sm font-bold hover:underline">Lihat Semua &rarr;</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Meja</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-400 text-xs">{{ $order->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4">{{ $order->table->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($order->status == 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[10px] font-bold uppercase tracking-wide">Pending</span>
                            @elseif($order->status == 'completed')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase tracking-wide">Selesai</span>
                            @elseif($order->status == 'paid')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold uppercase tracking-wide">Lunas</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-[10px] font-bold uppercase tracking-wide">{{ $order->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <p>Belum ada transaksi hari ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>