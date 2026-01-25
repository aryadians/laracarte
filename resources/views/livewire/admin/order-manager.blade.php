<div wire:poll.5s> <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Dapur & Pesanan</h1>
            <p class="text-slate-500 text-lg">Pantau pesanan masuk secara real-time.</p>
        </div>
        
        <div class="flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2 rounded-full text-xs font-bold animate-pulse">
            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
            LIVE UPDATE
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl shadow-sm flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="font-bold text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-slate-300">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-400">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Belum Ada Pesanan Aktif</h3>
            <p class="text-slate-500">Santai dulu sejenak, Chef! ☕</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($orders as $order)
                <div wire:key="order-{{ $order->id }}" class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden flex flex-col relative {{ $order->status == 'completed' ? 'ring-2 ring-green-500' : '' }}">
                    
                    <div class="px-6 py-4 {{ $order->status == 'pending' ? 'bg-indigo-600' : 'bg-green-600' }} text-white flex justify-between items-center transition-colors duration-300">
                        <div>
                            <span class="text-[10px] font-bold opacity-75 uppercase tracking-wider block">Meja</span>
                            <span class="text-2xl font-black">{{ $order->table->name ?? '?' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold opacity-75 uppercase tracking-wider block">Total</span>
                            <span class="font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="p-6 flex-1 bg-slate-50/50">
                        <div class="mb-4">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Pemesan</p>
                            <p class="font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ $order->customer_name }}
                            </p>
                        </div>
                        
                        @if($order->note)
                        <div class="mb-4 bg-yellow-50 p-3 rounded-xl border border-yellow-200">
                            <p class="text-[10px] font-bold text-yellow-600 uppercase tracking-widest mb-1">Catatan Khusus:</p>
                            <p class="text-sm text-yellow-800 font-medium italic">"{{ $order->note }}"</p>
                        </div>
                        @endif

                        <div class="space-y-3">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2">Menu Dipesan</p>
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-start text-sm">
                                    <div class="flex gap-3">
                                        <span class="font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md h-fit">{{ $item->quantity }}x</span>
                                        <span class="font-bold text-slate-700 leading-snug">{{ $item->product->name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-4 bg-white border-t border-slate-100 grid grid-cols-2 gap-3">
                        @if($order->status == 'pending')
                            <button wire:click="markAsCompleted({{ $order->id }})" wire:loading.attr="disabled" class="col-span-2 w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex justify-center items-center gap-2 disabled:opacity-50">
                                <span wire:loading.remove wire:target="markAsCompleted({{ $order->id }})">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Siap Saji
                                </span>
                                <span wire:loading wire:target="markAsCompleted({{ $order->id }})">Processing...</span>
                            </button>
                        @elseif($order->status == 'completed')
                            <button wire:click="markAsPaid({{ $order->id }})" wire:loading.attr="disabled" class="col-span-2 w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transition-all active:scale-95 flex justify-center items-center gap-2 disabled:opacity-50">
                                <span wire:loading.remove wire:target="markAsPaid({{ $order->id }})">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Terima Bayaran
                                </span>
                                <span wire:loading wire:target="markAsPaid({{ $order->id }})">Processing...</span>
                            </button>
                        @endif

                        <div class="col-span-2 flex justify-center pt-2">
                            <button wire:click="deleteOrder({{ $order->id }})" wire:confirm="Hapus pesanan ini?" class="text-xs font-bold text-red-400 hover:text-red-600 underline">
                                Batalkan Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>