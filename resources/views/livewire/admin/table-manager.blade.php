<div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Meja 🪑</h1>
            <p class="text-slate-500 text-lg">Buat meja baru dan cetak QR Code untuk ditempel.</p>
        </div>
        
        <div class="bg-white p-4 rounded-2xl shadow-lg border border-slate-100 flex flex-col md:flex-row gap-3 items-end">
            <div class="w-full md:w-64">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Nama Meja Baru</label>
                <input type="text" wire:model="name" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-300" placeholder="Contoh: Meja 10">
                @error('name') <span class="text-red-500 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
            </div>
            <button wire:click="store" class="px-6 py-2.5 bg-slate-900 hover:bg-black text-white font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Tambah Meja
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl shadow-sm flex items-center animate-fade-in-down">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="font-bold text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($tables as $table)
        <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-indigo-100/50 border border-slate-100 relative group hover:-translate-y-1 transition-transform duration-300">
            
            <div class="flex justify-between items-center mb-4">
                <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider">
                    {{ $table->name }}
                </span>
                <button wire:click="delete({{ $table->id }})" wire:confirm="Hapus meja ini?" class="text-slate-300 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>

            <div class="flex justify-center my-4">
                <div class="p-3 bg-white border-4 border-slate-900 rounded-xl shadow-sm">
                    {!! QrCode::size(140)->color(15, 23, 42)->generate(url('/table/' . $table->slug)) !!}
                </div>
            </div>

            <div class="text-center mb-6">
                <p class="text-[10px] text-slate-400 font-mono truncate px-2 bg-slate-50 py-1 rounded-md">
                    {{ url('/table/' . $table->slug) }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <a href="{{ url('/table/' . $table->slug) }}" target="_blank" class="flex items-center justify-center gap-2 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Tes Link
                </a>
                
                <button onclick="printQr('{{ $table->name }}', '{{ url('/table/' . $table->slug) }}')" class="flex items-center justify-center gap-2 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>
            </div>
        </div>
        @endforeach
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        function printQr(tableName, url) {
            // 1. Buat Jendela Popup Baru
            var printWindow = window.open('', '', 'height=600,width=500');

            // 2. Isi HTML Jendela tersebut
            printWindow.document.write('<html><head><title>Cetak QR - ' + tableName + '</title>');
            
            // 3. Tambahkan CSS Agar Tampilan Cetak Bagus
            printWindow.document.write(`
                <style>
                    body { 
                        font-family: 'Courier New', Courier, monospace; 
                        display: flex; 
                        flex-direction: column; 
                        align-items: center; 
                        justify-content: center; 
                        height: 100vh; 
                        margin: 0; 
                        background: #fff;
                    }
                    .card {
                        border: 2px dashed #000;
                        padding: 40px;
                        border-radius: 20px;
                        text-align: center;
                        width: 300px;
                    }
                    h1 { margin: 0 0 10px 0; font-size: 24px; font-weight: 900; }
                    p { margin: 0 0 20px 0; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; }
                    .qr-box { 
                        margin: 20px auto; 
                        padding: 10px;
                        border: 4px solid #000;
                        border-radius: 10px;
                        display: inline-block;
                    }
                    .footer { font-size: 10px; margin-top: 20px; color: #555; }
                </style>
            `);
            printWindow.document.write('</head><body>');
            
            // 4. Isi Konten Kartu
            printWindow.document.write(`
                <div class="card">
                    <h1>SCAN HERE</h1>
                    <p>UNTUK PESAN MENU</p>
                    
                    <div id="qr-target" class="qr-box"></div>
                    
                    <h1>${tableName}</h1>
                    <div class="footer">LaraCarte Resto System</div>
                </div>
            `);

            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // 5. Generate QR Code di dalam Jendela Baru
            setTimeout(function() {
                var script = printWindow.document.createElement('script');
                script.src = "https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js";
                script.onload = function() {
                    new printWindow.QRCode(printWindow.document.getElementById("qr-target"), {
                        text: url,
                        width: 200,
                        height: 200
                    });
                    
                    // 6. Trigger Print
                    setTimeout(function() {
                        printWindow.focus();
                        printWindow.print();
                        printWindow.close();
                    }, 500);
                };
                printWindow.document.head.appendChild(script);
            }, 100);
        }
    </script>
    @endpush
</div>