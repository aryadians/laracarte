<div class="p-6"> <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Halaman Kasir (POS)</h2>
            <p class="text-slate-500 text-sm">Kelola pembayaran dan cetak struk dengan Pajak & Service.</p>
        </div>
        
        <div wire:poll.10s class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full flex items-center gap-2 border border-green-100">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            Live Update
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Meja</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($orders as $order)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs">#{{ $order->id }}</td>
                    <td class="px-6 py-4">{{ $order->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}</td>
                    <td class="px-6 py-4 font-bold">{{ $order->table->name ?? 'Takeaway' }}</td>
                    <td class="px-6 py-4">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                            {{ $order->status == 'paid' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $order->status == 'served' ? 'bg-blue-100 text-blue-700' : '' }} 
                            {{ $order->status == 'completed' ? 'bg-gray-100 text-gray-700' : '' }}">
                            
                            @if($order->status == 'served') Siap Bayar
                            @elseif($order->status == 'paid') Lunas
                            @elseif($order->status == 'completed') Selesai
                            @else {{ $order->status }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button wire:click="openDetail({{ $order->id }})" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all active:scale-95">
                            Bayar / Struk
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">Belum ada pesanan yang perlu dibayar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $orders->links() }}
        </div>
    </div>

    @if($selectedOrder)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 class="text-xl font-black text-slate-800">Order #{{ $selectedOrder->id }}</h3>
                    <p class="text-sm text-slate-500">{{ $selectedOrder->customer_name }} - {{ $selectedOrder->table->name ?? 'Takeaway' }}</p>
                </div>
                <button wire:click="closeDetail" class="w-8 h-8 flex items-center justify-center bg-white rounded-full text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div>
                    <h4 class="font-bold text-slate-700 mb-4 uppercase text-xs tracking-wider">Rincian Pesanan</h4>
                    
                    <div class="space-y-3 pb-4 mb-2">
                        @foreach($selectedOrder->items as $item)
                        <div class="flex justify-between items-start text-sm">
                            <div>
                                <p class="font-bold text-slate-800">{{ $item->product->name }}</p>
                                <p class="text-xs text-slate-400">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-bold text-slate-800">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t border-dashed border-slate-200 pt-4 space-y-2 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span>Service Charge (5%)</span>
                            <span>Rp {{ number_format($service, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span>PB1 / PPN (11%)</span>
                            <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-slate-200">
                        <span class="font-bold text-slate-800">Grand Total</span>
                        <span class="font-black text-2xl text-indigo-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex flex-col justify-between h-full">
                    
                    @if($selectedOrder->status == 'paid' || $selectedOrder->status == 'completed')
                        <div class="text-center py-10 flex flex-col justify-center h-full">
                            <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="font-black text-xl text-slate-800">LUNAS</h3>
                            <p class="text-slate-500 text-sm mb-6">Transaksi Selesai</p>
                            
                            <button onclick="printReceipt()" class="w-full bg-slate-800 text-white py-3 rounded-xl font-bold hover:bg-slate-900 flex items-center justify-center gap-2 shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak Struk
                            </button>
                        </div>
                    @else
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Uang Diterima (Rp)</label>
                            <input wire:model.live="paymentAmount" type="number" class="w-full text-right text-3xl font-black text-slate-800 bg-white border border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-4" placeholder="0">
                            @error('paymentAmount') <span class="text-red-500 text-xs font-bold block mt-2">{{ $message }}</span> @enderror
                            
                            <div class="mt-6 flex justify-between items-center bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                <span class="font-bold text-slate-500 text-sm">Kembalian:</span>
                                <span class="font-black text-2xl {{ $changeAmount < 0 ? 'text-red-500' : 'text-green-500' }}">
                                    Rp {{ number_format($changeAmount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3 mt-auto pt-4">
                            <button wire:click="markAsPaid" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex justify-center items-center gap-2">
                                <span>💵</span> Bayar Tunai
                            </button>

                            <div class="relative flex py-1 items-center">
                                <div class="flex-grow border-t border-gray-200"></div>
                                <span class="flex-shrink-0 mx-4 text-gray-400 text-xs uppercase font-bold">Atau</span>
                                <div class="flex-grow border-t border-gray-200"></div>
                            </div>

                            <button wire:click="openQris" class="w-full bg-white border-2 border-slate-200 text-slate-700 py-3 rounded-xl font-bold hover:bg-slate-50 hover:border-indigo-500 hover:text-indigo-600 transition-all active:scale-95 flex justify-center items-center gap-2">
                                <span>📱</span> Tampilkan QRIS
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isQrisModalOpen)
    <div class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl animate-zoom-in relative">
            
            <button wire:click="closeQris" class="absolute top-4 right-4 bg-slate-100 rounded-full p-2 text-slate-500 hover:bg-red-100 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="p-8 text-center">
                <h3 class="text-xl font-black text-slate-800 mb-1">Scan QRIS</h3>
                <p class="text-sm text-slate-500 mb-6">Scan menggunakan GoPay, OVO, Dana, atau Mobile Banking.</p>
                
                <div class="bg-white p-2 border-2 border-slate-800 rounded-2xl inline-block mb-4 shadow-lg">
                    <img src="{{ asset('qris.jpg') }}" class="w-48 h-48 object-contain" alt="QRIS Code">
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 mb-6">
                    <p class="text-xs font-bold text-yellow-700 uppercase">Total Tagihan:</p>
                    <p class="text-2xl font-black text-slate-800">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                </div>

                <div class="space-y-3">
                    <button wire:click="markAsQrisPaid" class="w-full bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 shadow-lg shadow-green-500/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Pembayaran Diterima
                    </button>
                    
                    <p class="text-xs text-slate-400">Pastikan uang sudah masuk ke rekening sebelum konfirmasi.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($selectedOrder) 
    <div id="receipt-area" class="hidden">
        <div class="receipt-content">
            <div class="text-center">
                <h2 class="font-bold text-xl uppercase">LaraCarte Resto</h2>
                <p class="text-[10px] leading-tight mt-1">Jl. Teknologi No. 1, Jakarta</p>
                <p class="text-[10px]">Telp: 0812-3456-7890</p>
            </div>
            
            <div class="dashed-line my-2"></div>

            <div class="text-[10px] space-y-0.5">
                <div class="flex justify-between"><span>No. Order</span><span class="font-bold">#{{ $selectedOrder->id }}</span></div>
                <div class="flex justify-between"><span>Tgl</span><span>{{ $selectedOrder->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}</span></div>
                <div class="flex justify-between"><span>Meja</span><span>{{ $selectedOrder->table->name ?? 'Takeaway' }}</span></div>
                <div class="flex justify-between"><span>Kasir</span><span>{{ Auth::user()->name ?? 'Admin' }}</span></div>
            </div>

            <div class="dashed-line my-2"></div>

            <div class="text-[10px]">
                @foreach($selectedOrder->items as $item)
                <div class="mb-1">
                    <div class="font-bold">{{ $item->product->name }}</div>
                    <div class="flex justify-between pl-2">
                        <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="dashed-line my-2"></div>

            <div class="text-[10px] space-y-0.5 text-right">
                <div class="flex justify-between"><span>Subtotal</span><span>{{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Service (5%)</span><span>{{ number_format($service, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>PB1 (11%)</span><span>{{ number_format($tax, 0, ',', '.') }}</span></div>
            </div>

            <div class="dashed-line my-2"></div>

            <div class="text-xs font-bold space-y-1">
                <div class="flex justify-between text-sm">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-normal text-[10px]">
                    <span>Tunai</span>
                    <span>{{ number_format((int)$paymentAmount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-normal text-[10px]">
                    <span>Kembali</span>
                    <span>{{ number_format((int)$changeAmount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="dashed-line my-4"></div>
            <div class="text-center text-[10px]">
                <p class="font-bold">TERIMA KASIH</p>
                <p>Harga sudah termasuk PPN</p>
                <p class="mt-1">Wifi: LaraCarte_Free</p>
            </div>
        </div>
    </div>
    @endif

    <script>
        function printReceipt() {
            var content = document.getElementById('receipt-area').innerHTML;
            
            // Buat Iframe
            var iframe = document.createElement('iframe');
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            document.body.appendChild(iframe);
            
            // Isi Konten Iframe & Print
            var doc = iframe.contentWindow.document;
            doc.open();
            doc.write('<html><head><style>body{font-family:monospace;width:58mm;font-size:10pt;margin:0;padding:5px;}.dashed-line{border-top:1px dashed #000;width:100%;margin:5px 0;}.text-center{text-align:center}.flex{display:flex;justify-content:space-between}.font-bold{font-weight:bold}.text-right{text-align:right}.mb-1{margin-bottom:2px}.space-y-0.5 > * + * {margin-top: 2px;} .space-y-1 > * + * {margin-top: 4px;}</style></head><body onload="window.print();window.close();">' + content + '</body></html>');
            doc.close();
            
            // Hapus Iframe setelah 1 detik
            setTimeout(function() { document.body.removeChild(iframe); }, 1000);
        }
    </script>
</div>