<div x-on:echo:kitchen,OrderCreated="$wire.$refresh()" class="p-6"> <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Dapur & Pesanan</h1>
            <p class="text-slate-500 text-lg">Pantau antrian pesanan yang masuk.</p>
        </div>
        
        <div class="flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2 rounded-full text-xs font-bold animate-pulse border border-green-200">
            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
            LIVE KITCHEN
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl shadow-sm flex items-center animate-fade-in-down">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="font-bold text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-300">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-700">Dapur Kosong</h3>
            <p class="text-slate-400 font-medium">Belum ada pesanan masuk. Santai dulu, Chef! ☕</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($orders as $order)
                <div wire:key="order-{{ $order->id }}" class="bg-white rounded-3xl shadow-lg hover:shadow-xl transition-shadow border border-slate-100 overflow-hidden flex flex-col relative">
                    
                    <div class="px-6 py-4 flex justify-between items-center text-white
                        {{ $order->status == 'pending' ? 'bg-red-500' : '' }}
                        {{ $order->status == 'cooking' ? 'bg-orange-500' : '' }}
                        {{ $order->status == 'served' ? 'bg-blue-500' : '' }}">
                        
                        <div>
                            <span class="text-[10px] font-bold opacity-80 uppercase tracking-widest block">Meja</span>
                            <span class="text-2xl font-black">{{ $order->table->name ?? '?' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold opacity-80 uppercase tracking-widest block">Status</span>
                            <span class="font-bold bg-white/20 px-2 py-0.5 rounded text-xs">
                                @if($order->status == 'pending') MENUNGGU
                                @elseif($order->status == 'cooking') DIMASAK
                                @elseif($order->status == 'served') DISAJIKAN
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="p-6 flex-1 bg-slate-50/50">
                        <div class="mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-slate-400 shadow-sm border border-slate-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Pemesan</p>
                                <p class="font-bold text-slate-800 text-sm">{{ $order->customer_name }}</p>
                            </div>
                            <div class="ml-auto text-xs text-slate-400 font-mono">
                                {{ $order->created_at->format('H:i') }}
                            </div>
                        </div>
                        
                        @if($order->note)
                        <div class="mb-4 bg-yellow-50 p-3 rounded-xl border border-yellow-200 relative">
                            <svg class="w-4 h-4 text-yellow-400 absolute top-2 right-2" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"></path></svg>
                            <p class="text-[10px] font-bold text-yellow-600 uppercase tracking-widest mb-1">Catatan:</p>
                            <p class="text-sm text-yellow-900 font-medium italic leading-snug">"{{ $order->note }}"</p>
                        </div>
                        @endif

                        <div class="space-y-3">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Menu Dipesan</p>
                            @foreach($order->items as $item)
                                <div class="flex gap-3 items-start">
                                    <span class="font-black text-slate-700 bg-white border border-slate-200 px-2 py-0.5 rounded-md text-sm shadow-sm min-w-[30px] text-center">{{ $item->quantity }}x</span>
                                    <div>
                                        <span class="font-bold text-slate-700 text-sm leading-snug pt-0.5 block">{{ $item->product->name }}</span>
                                        @if($item->selectedVariants->isNotEmpty())
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach($item->selectedVariants as $v)
                                                    <span class="text-[10px] bg-indigo-50 text-indigo-700 px-1.5 py-0.5 rounded border border-indigo-100 font-bold">
                                                        {{ $v->option_name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-4 bg-white border-t border-slate-100 flex flex-col gap-2">
                        
                        @if($order->status == 'pending')
                            <button wire:click="markAsCooking({{ $order->id }})" wire:loading.attr="disabled" class="w-full py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/30 transition-all active:scale-95 flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                                Mulai Masak
                            </button>
                        
                        @elseif($order->status == 'cooking')
                            <button wire:click="markAsServed({{ $order->id }})" wire:loading.attr="disabled" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-95 flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Siap Saji
                            </button>
                        @elseif($order->status == 'served')
                            <div class="text-center py-2 bg-slate-50 rounded-xl border border-slate-100 text-slate-400 font-bold text-sm">
                                Menunggu Pembayaran...
                            </div>
                        @endif

                        <div class="flex justify-center mt-2">
                            <button wire:click="deleteOrder({{ $order->id }})" wire:confirm="Yakin ingin membatalkan pesanan ini?" class="text-[10px] font-bold text-slate-400 hover:text-red-500 transition-colors uppercase tracking-widest">
                                Batalkan Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>