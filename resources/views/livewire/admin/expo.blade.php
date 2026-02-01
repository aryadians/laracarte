<div wire:poll.5s class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Expo / Runner 🏃💨</h1>
            <p class="text-slate-500 text-lg">Pesanan siap saji yang perlu segera diantar.</p>
        </div>
        
        <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-xs font-bold flex items-center gap-2 animate-pulse border border-blue-200">
            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
            REAL-TIME EXPO
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl shadow-sm flex items-center animate-fade-in-down">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="font-bold text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-300">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-700">Semua Terantar!</h3>
            <p class="text-slate-400 font-medium">Tidak ada makanan yang menunggu di pass.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($orders as $order)
                <div wire:key="expo-{{ $order->id }}" class="bg-white rounded-3xl shadow-xl border-l-8 border-blue-500 overflow-hidden flex flex-col relative animate-slide-in-right">
                    
                    <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Meja</span>
                            <span class="text-3xl font-black text-slate-800">{{ $order->table->name ?? '?' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Waktu Saji</span>
                            <span class="font-mono text-lg font-bold text-blue-600">{{ $order->updated_at->format('H:i') }}</span>
                        </div>
                    </div>

                    <div class="p-6 flex-1">
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                                <div class="flex gap-3 items-start">
                                    <span class="font-black text-slate-700 bg-white border border-slate-200 px-2 py-0.5 rounded-md text-sm shadow-sm min-w-[30px] text-center">{{ $item->quantity }}x</span>
                                    <div>
                                        <span class="font-bold text-slate-800 text-lg leading-snug pt-0.5 block">{{ $item->product->name }}</span>
                                        @if($item->selectedVariants->isNotEmpty())
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach($item->selectedVariants as $v)
                                                    <span class="text-[10px] bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded border border-yellow-200 font-bold">
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

                    <div class="p-4 bg-white border-t border-slate-100">
                        <button wire:click="markAsCompleted({{ $order->id }})" class="w-full py-4 bg-green-500 hover:bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-500/30 transition-all active:scale-95 flex justify-center items-center gap-2 text-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Sudah Diantar
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>