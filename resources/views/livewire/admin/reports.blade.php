<div class="p-6 space-y-6">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-bold uppercase">Omzet Hari Ini</p>
                <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($summary['today'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-bold uppercase">Omzet Bulan Ini</p>
                <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($summary['month'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-bold uppercase">Total Transaksi</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $summary['orders'] }} Pesanan</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h4 class="text-lg font-bold text-slate-800 mb-4">Grafik Pendapatan (7 Hari Terakhir)</h4>
            <div class="w-full h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h4 class="text-lg font-bold text-slate-800 mb-4">🔥 Menu Terlaris</h4>
            <div class="space-y-4">
                @forelse($topProducts as $index => $item)
                <div class="flex items-center gap-4 border-b border-slate-50 pb-3 last:border-0 last:pb-0">
                    <span class="text-lg font-black text-slate-300">#{{ $index + 1 }}</span>
                    @if($item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-10 h-10 rounded-lg object-cover bg-slate-100">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-slate-100"></div>
                    @endif
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 text-sm">{{ $item->product->name }}</p>
                        <p class="text-xs text-slate-500">{{ $item->total_qty }} terjual</p>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 text-sm text-center py-4">Belum ada data penjualan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            // Ambil data dari Livewire Component
            const labels = @json($chartLabels);
            const data = @json($chartValues);

            new Chart(ctx, {
                type: 'line', // Jenis grafik: Line, Bar, Pie
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data,
                        borderColor: '#4F46E5', // Warna Garis (Indigo-600)
                        backgroundColor: 'rgba(79, 70, 229, 0.1)', // Warna Area Bawah
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4F46E5',
                        pointRadius: 5,
                        fill: true,
                        tension: 0.4 // Membuat garis melengkung halus
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [5, 5] },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'k';
                                }
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</div>