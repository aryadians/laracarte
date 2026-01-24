<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Laporan Penjualan 📈</h2>
            <p class="text-slate-500">Data pesanan yang sudah selesai dan dibayar.</p>
        </div>

        <div class="flex gap-3 bg-white p-2 rounded-xl shadow-sm border border-slate-100">
            <input wire:model.live="date" type="date" class="border-none bg-slate-50 rounded-lg text-sm font-bold text-slate-600 focus:ring-0">
            <input wire:model.live="search" type="text" placeholder="Cari nama..." class="border-none bg-slate-50 rounded-lg text-sm pl-4 w-40 focus:ring-0">
            @if($date || $search)
                <button wire:click="resetFilters" class="px-3 text-red-500 hover:bg-red-50 rounded-lg transition">
                    ✕
                </button>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-xs border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Meja</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($orders as $order)
                <tr class="hover:bg-indigo-50/30 transition-colors">
                    <td class="px-6 py-4 font-mono font-bold text-indigo-600">#{{ $order->id }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-bold">{{ $order->table->name }}</span>
                    </td>
                    <td class="px-6 py-4 font-black text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-xs text-slate-400">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="printReceipt({{ $order->id }})" class="bg-slate-800 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-slate-900 transition-all flex items-center gap-1 mx-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Struk
                        </button>
                        
                        <div id="receipt-{{ $order->id }}" class="hidden print-area">
                            <div style="text-align: center; font-family: monospace; width: 300px; margin: 0 auto; padding: 20px;">
                                <h2 style="margin: 0; font-size: 1.2rem;">LARACARTE</h2>
                                <p style="margin: 0; font-size: 0.8rem; color: #666;">Jl. Coding No. 1, Internet</p>
                                <hr style="border: 1px dashed #000; margin: 10px 0;">
                                
                                <div style="text-align: left; font-size: 0.9rem;">
                                    <p>Order: #{{ $order->id }}</p>
                                    <p>Tgl: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    <p>Meja: {{ $order->table->name }}</p>
                                    <p>Cust: {{ $order->customer_name }}</p>
                                </div>
                                <hr style="border: 1px dashed #000; margin: 10px 0;">
                                
                                <table style="width: 100%; font-size: 0.9rem; text-align: left;">
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->quantity }}x {{ $item->product->name }}</td>
                                        <td style="text-align: right;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                
                                <hr style="border: 1px dashed #000; margin: 10px 0;">
                                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1rem;">
                                    <span>TOTAL</span>
                                    <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                                <hr style="border: 1px dashed #000; margin: 10px 0;">
                                <p style="text-align: center; margin-top: 10px; font-size: 0.8rem;">Terima Kasih!</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                        Belum ada transaksi selesai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>

    <script>
        function printReceipt(id) {
            var printContents = document.getElementById('receipt-' + id).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload(); // Reload agar event livewire jalan lagi
        }
    </script>
</div>