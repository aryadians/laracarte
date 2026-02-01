<div wire:poll.10s>
    {{-- Notifikasi Panggilan Pelayan (Merah Mencolok) --}}
    @if($waitressCalls->isNotEmpty())
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
                var audio = document.getElementById("notifSound");
                if(audio) audio.play().catch(e => console.log("Audio autoplay blocked"));
            </script>
        </div>
    @endif

    {{-- Banner Selamat Datang --}}
    <div class="bg-slate-900 rounded-3xl p-8 mb-8 relative overflow-hidden shadow-2xl shadow-indigo-500/20">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-16 -mt-16 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -ml-16 -mb-16 animate-blob animation-delay-2000"></div>
        <div class="relative z-10">
            <h2 class="text-3xl font-black text-white mb-2">Halo, Admin LaraCarte! 👋</h2>
            <p class="text-slate-400 text-lg">Pantau performa restoranmu secara real-time di sini.</p>
        </div>
    </div>

    {{-- Grid Statistik Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- Pendapatan --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-transform">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center font-bold text-lg">💰</div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Pendapatan Hari Ini</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
        </div>

        {{-- Pesanan Aktif --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-transform">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center font-bold text-lg">🔥</div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Pesanan Aktif (KDS)</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800">{{ $activeOrders }} <span class="text-sm font-medium text-slate-400">Antrian</span></h3>
        </div>

        {{-- Stok Menipis --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-transform">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center font-bold text-lg">⚠️</div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Stok Menipis</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800">{{ $lowStockCount }} <span class="text-sm font-medium text-slate-400">Produk</span></h3>
            @if($lowStockCount > 0)
                <a href="{{ route('admin.products') }}" class="absolute bottom-6 right-6 text-xs text-red-500 font-bold hover:underline">Cek &rarr;</a>
            @endif
        </div>

        {{-- Produk Terlaris --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-transform">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-lg">🏆</div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Menu Terlaris</p>
            </div>
            @if($topProduct)
                <h3 class="text-lg font-black text-slate-800 line-clamp-1" title="{{ $topProduct->product->name }}">{{ $topProduct->product->name }}</h3>
                <p class="text-xs text-slate-500">{{ $topProduct->total_qty }} porsi terjual hari ini</p>
            @else
                <h3 class="text-lg font-bold text-slate-300">Belum ada data</h3>
            @endif
        </div>
    </div>

    {{-- Tabel Transaksi Terkini --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Aktivitas Terkini</h3>
            <a href="{{ route('admin.kitchen') }}" class="text-indigo-600 text-sm font-bold hover:underline">Lihat Dapur &rarr;</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-400 text-xs">{{ $order->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 block">{{ $order->customer_name }}</span>
                            <span class="text-xs text-slate-400">{{ $order->table->name ?? 'Takeaway' }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColor = match($order->status) {
                                    'paid' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-red-100 text-red-700',
                                    'cooking' => 'bg-orange-100 text-orange-700',
                                    'served' => 'bg-blue-100 text-blue-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                                $statusLabel = match($order->status) {
                                    'paid' => 'Lunas',
                                    'pending' => 'Baru',
                                    'cooking' => 'Masak',
                                    'served' => 'Saji',
                                    default => $order->status
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <p>Belum ada transaksi hari ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
