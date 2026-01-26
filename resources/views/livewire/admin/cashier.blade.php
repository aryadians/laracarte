<div class="p-6"> <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Halaman Kasir (POS)</h2>
            <p class="text-slate-500 text-sm">Kelola pembayaran dan cetak struk.</p>
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
                    {{-- FIX TIMEZONE: Paksa ke Asia/Jakarta --}}
                    <td class="px-6 py-4">{{ $order->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}</td>
                    <td class="px-6 py-4 font-bold">{{ $order->table->name ?? 'Takeaway' }}</td>
                    <td class="px-6 py-4">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                            {{ $order->status == 'paid' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $order->status == 'pending' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $order->status == 'cooking' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $order->status == 'served' ? 'bg-blue-100 text-blue-700' : '' }} 
                            {{ $order->status == 'completed' ? 'bg-gray-100 text-gray-700' : '' }}">
                            
                            @if($order->status == 'pending') Menunggu
                            @elseif($order->status == 'cooking') Dimasak
                            @elseif($order->status == 'served') Disajikan
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
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">Belum ada pesanan masuk.</td>
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
                    <h3 class="text-xl font-black text-slate-800">Kasir: Order #{{ $selectedOrder->id }}</h3>
                    <p class="text-sm text-slate-500">{{ $selectedOrder->customer_name }} - {{ $selectedOrder->table->name }}</p>
                </div>
                <button wire:click="closeDetail" class="w-8 h-8 flex items-center justify-center bg-white rounded-full text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div>
                    <h4 class="font-bold text-slate-700 mb-4 uppercase text-xs tracking-wider">Rincian Item</h4>
                    <div class="space-y-3 border-b border-slate-100 pb-4 mb-4">
                        @foreach($selectedOrder->items as $item)
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-slate-800 text-sm">{{ $item->product->name }}</p>
                                <p class="text-xs text-slate-400">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-bold text-slate-800 text-sm">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-slate-500">Total Tagihan</span>
                        <span class="font-black text-2xl text-indigo-600">Rp {{ number_format($selectedOrder->total_price, 0, ',', '.') }}</span>
                    </div>

                    @if($selectedOrder->note)
                    <div class="mt-4 bg-yellow-50 p-3 rounded-xl border border-yellow-100">
                        <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Catatan:</p>
                        <p class="text-sm text-yellow-800 italic">"{{ $selectedOrder->note }}"</p>
                    </div>
                    @endif
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
                                Cetak Struk (Thermal)
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

                        <button wire:click="markAsPaid" class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all active:scale-95 mt-auto">
                            Terima Pembayaran
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="receipt-area" class="hidden">
        <div class="receipt-content">
            <div class="text-center">
                <h2 class="font-bold text-xl uppercase">LaraCarte Resto</h2>
                <p class="text-[10px] leading-tight mt-1">Jl. Teknologi No. 1, Jakarta Selatan</p>
                <p class="text-[10px]">Telp: 0812-3456-7890</p>
            </div>
            
            <div class="dashed-line my-2"></div>

            <div class="text-[10px] space-y-0.5">
                <div class="flex justify-between">
                    <span>No. Order</span>
                    <span class="font-bold">#{{ $selectedOrder->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tanggal</span>
                    <span>{{ $selectedOrder->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pelanggan</span>
                    <span>{{ substr($selectedOrder->customer_name, 0, 15) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Meja</span>
                    <span>{{ $selectedOrder->table->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir</span>
                    <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
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

            <div class="text-xs font-bold space-y-1">
                <div class="flex justify-between text-sm">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($selectedOrder->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-normal">
                    <span>Tunai</span>
                    <span>Rp {{ number_format((int)$paymentAmount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-normal">
                    <span>Kembali</span>
                    <span>Rp {{ number_format((int)$changeAmount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="dashed-line my-4"></div>
            <div class="text-center text-[10px]">
                <p class="font-bold">TERIMA KASIH</p>
                <p>Silakan Datang Kembali!</p>
                <p class="mt-1">Wifi: LaraCarte_Free</p>
            </div>
        </div>
    </div>
    @endif

    <script>
        function printReceipt() {
            window.print();
        }
    </script>

    <style>
    /* Style Khusus Garis Putus-putus */
    .dashed-line {
        border-top: 1px dashed #000;
        width: 100%;
        height: 1px;
    }

    @media print {
        /* Sembunyikan elemen website lain */
        body * {
            visibility: hidden;
            height: 0;
            overflow: hidden;
        }

        /* Tampilkan Area Struk */
        #receipt-area, #receipt-area * {
            display: block !important; 
            visibility: visible;
            height: auto;
        }

        #receipt-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 58mm; /* Lebar Kertas Thermal 58mm */
            padding: 2mm; 
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace; /* Font Monospace agar rapi */
            font-size: 10pt;
            background: white;
            color: black;
            line-height: 1.2;
        }

        /* Hapus margin default browser saat print */
        @page { 
            margin: 0; 
            size: auto; 
        }
    }
    </style>

</div>