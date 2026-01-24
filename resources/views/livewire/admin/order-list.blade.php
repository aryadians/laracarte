<div wire:poll.5s> <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Antrian Dapur 👨‍🍳</h2>
            <p class="text-slate-500">Monitor pesanan real-time. Data akan refresh otomatis.</p>
        </div>
        
        <div class="flex gap-3 bg-white p-2 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-2 px-3 py-1 bg-yellow-50 text-yellow-700 rounded-lg text-xs font-bold border border-yellow-100">
                <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span> Pending
            </div>
            <div class="flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold border border-blue-100">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Memasak
            </div>
            <div class="flex items-center gap-2 px-3 py-1 bg-green-50 text-green-700 rounded-lg text-xs font-bold border border-green-100">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span> Siap Saji
            </div>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700">Tidak ada antrian</h3>
            <p class="text-slate-400">Dapur sedang sepi, santai dulu...</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($orders as $order)
                @php
                    $statusStyles = match($order->status) {
                        'pending' => 'border-l-yellow-400 shadow-yellow-100/50 ring-yellow-400/20',
                        'cooking' => 'border-l-blue-500 shadow-blue-100/50 ring-blue-500/20',
                        'served'  => 'border-l-green-500 shadow-green-100/50 ring-green-500/20',
                        default   => 'border-l-slate-200 shadow-slate-200'
                    };
                @endphp

                <div class="bg-white rounded-3xl p-5 border border-slate-100 border-l-[6px] shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative {{ $statusStyles }}">
                    
                    <div class="flex justify-between items-start mb-4 border-b border-slate-50 pb-4">
                        <div class="flex flex-col">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-bold shadow-md shadow-slate-900/20">
                                🍽️ {{ $order->table->name }}
                            </span>
                        </div>

                        <div class="text-right">
                            <span class="block text-[0.65rem] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Status</span>
                            <span class="font-black text-sm uppercase px-2 py-0.5 rounded {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-700 animate-pulse' : 'text-slate-700 bg-slate-100' }}">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="font-bold text-slate-800 text-xl leading-tight">{{ $order->customer_name }}</h3>
                        <p class="text-xs text-slate-400 font-medium flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Order masuk: {{ $order->created_at->format('H:i') }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-3 mb-4 space-y-2 max-h-48 overflow-y-auto custom-scrollbar border border-slate-100">
                        @foreach($order->items as $item)
                        <div class="flex justify-between items-start text-sm">
                            <div class="flex items-start gap-2">
                                <span class="font-bold text-white bg-indigo-500 px-1.5 py-0.5 rounded text-xs min-w-[24px] text-center mt-0.5">{{ $item->quantity }}x</span>
                                <span class="text-slate-700 font-medium leading-snug">{{ $item->product->name }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($order->note)
                    <div class="bg-red-50 p-3 rounded-xl mb-5 border border-red-100">
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            <span class="text-[0.65rem] font-bold text-red-500 uppercase tracking-wider">Catatan Khusus:</span>
                        </div>
                        <p class="text-sm text-red-800 font-medium italic leading-relaxed">"{{ $order->note }}"</p>
                    </div>
                    @else
                    <div class="mb-5"></div> @endif

                    <div class="mt-auto">
                        @if($order->status == 'pending')
                            <button wire:click="updateStatus({{ $order->id }}, 'cooking')" class="w-full py-3.5 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all active:scale-95 flex justify-center items-center gap-2 group">
                                <span class="group-hover:animate-bounce">👨‍🍳</span> Mulai Masak
                            </button>
                        @elseif($order->status == 'cooking')
                            <button wire:click="updateStatus({{ $order->id }}, 'served')" class="w-full py-3.5 bg-green-600 text-white rounded-xl font-bold shadow-lg shadow-green-500/30 hover:bg-green-700 hover:shadow-green-600/40 transition-all active:scale-95 flex justify-center items-center gap-2 group">
                                <span class="group-hover:animate-pulse">🔔</span> Siap Saji / Antar
                            </button>
                        @elseif($order->status == 'served')
                            <button wire:click="updateStatus({{ $order->id }}, 'paid')" class="w-full py-3.5 bg-slate-800 text-white rounded-xl font-bold shadow-lg shadow-slate-800/30 hover:bg-slate-900 transition-all active:scale-95 flex justify-center items-center gap-2">
                                💰 Selesai & Bayar
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>