<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Riwayat Transaksi 📜</h2>
            <p class="text-slate-500">Rekap data pesanan yang sudah lunas.</p>
        </div>

        <div class="flex flex-wrap gap-3 items-center">
            <button wire:click="exportCsv" wire:loading.attr="disabled" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl font-bold transition-all active:scale-95 shadow-lg shadow-green-500/30 text-sm h-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                
                <span wire:loading.remove wire:target="exportCsv">Export CSV</span>
                <span wire:loading wire:target="exportCsv">Proses...</span>
            </button>

            <div class="flex gap-2 bg-white p-1 rounded-xl shadow-sm border border-slate-100 h-10 items-center">
                <input wire:model.live="date" type="date" class="border-none bg-slate-50 rounded-lg text-xs font-bold text-slate-600 focus:ring-0 h-8">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama / ID..." class="border-none bg-slate-50 rounded-lg text-xs pl-3 w-32 focus:ring-0 h-8">
                
                @if($date || $search)
                <button wire:click="resetFilters" class="px-2 text-red-500 hover:bg-red-50 rounded-lg transition h-8 flex items-center" title="Reset Filter">
                    ✕
                </button>
                @endif
            </div>
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
                    <th class="px-6 py-4">Waktu (WIB)</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($orders as $order)
                <tr class="hover:bg-indigo-50/30 transition-colors">
                    <td class="px-6 py-4 font-mono font-bold text-indigo-600">#{{ $order->id }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-bold">{{ $order->table->name ?? 'Takeaway' }}</span>
                    </td>
                    <td class="px-6 py-4 font-black text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-xs text-slate-400">
                        {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="printReceipt({{ $order->id }})" class="bg-slate-800 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-slate-900 transition-all flex items-center gap-1 mx-auto shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Struk
                        </button>
                        
                        <div id="receipt-{{ $order->id }}" class="hidden">
                            <div style="font-family: 'Courier New', monospace; width: 58mm; margin: 0 auto; padding: 10px; font-size: 10pt; line-height: 1.2;">
                                <div style="text-align: center;">
                                    <h2 style="margin: 0; font-size: 12pt; font-weight: bold;">LARACARTE</h2>
                                    <p style="margin: 0; font-size: 8pt;">Jl. Teknologi No. 1</p>
                                </div>
                                <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>
                                
                                <div style="font-size: 8pt;">
                                    <div>Order: #{{ $order->id }}</div>
                                    <div>Tgl: {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}</div>
                                    <div>Meja: {{ $order->table->name ?? '-' }}</div>
                                    <div>Cust: {{ substr($order->customer_name, 0, 15) }}</div>
                                </div>
                                <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>
                                
                                <table style="width: 100%; font-size: 9pt; border-collapse: collapse;">
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">{{ $item->product->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td style="text-align: right;">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                
                                <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>
                                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 10pt;">
                                    <span>TOTAL</span>
                                    <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                                <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>
                                <div style="text-align: center; margin-top: 10px; font-size: 8pt;">
                                    <p>Terima Kasih!</p>
                                    <p>Simpan struk ini sbg bukti.</p>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 flex flex-col items-center justify-center">
                        <div class="bg-slate-50 p-4 rounded-full mb-3">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <p>Belum ada riwayat transaksi.</p>
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
            // 1. Ambil konten struk dari div tersembunyi
            var content = document.getElementById('receipt-' + id).innerHTML;
            
            // 2. Buat elemen Iframe sementara
            var iframe = document.createElement('iframe');
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            
            // 3. Masukkan Iframe ke dalam body
            document.body.appendChild(iframe);
            
            // 4. Tulis konten ke dalam Iframe dan cetak
            var doc = iframe.contentWindow.document;
            doc.open();
            doc.write('<html><body onload="window.print(); window.close();">' + content + '</body></html>');
            doc.close();
            
            // 5. Hapus Iframe setelah selesai (delay 1 detik)
            setTimeout(function() {
                document.body.removeChild(iframe);
            }, 1000);
        }
    </script>
</div>