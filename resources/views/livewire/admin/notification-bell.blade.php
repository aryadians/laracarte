<div wire:poll.5s class="relative" x-data="{ open: false }">
    
    <button 
        @click="open = !open; if(open) { $wire.markAsRead() }" 
        @click.outside="open = false" 
        class="relative p-2 text-slate-400 hover:text-indigo-600 transition-colors bg-white rounded-full shadow-sm border border-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 active:scale-95">
        
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        
        @if($count > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full animate-pulse shadow-sm">
                {{ $count }}
            </span>
        @endif
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-[100] origin-top-right"
         style="display: none;">
        
        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pesanan Pending</h3>
        </div>

        <div class="max-h-80 overflow-y-auto custom-scrollbar">
            @forelse($notifications as $notif)
                <a href="{{ route('admin.kitchen') }}" wire:navigate class="block px-4 py-3 hover:bg-indigo-50 transition-colors border-b border-slate-50 last:border-0 group">
                    <div class="flex justify-between items-start mb-1">
                        <div class="flex items-center gap-2">
                             @if($notif->created_at > session('last_notification_check'))
                                <span class="w-2 h-2 bg-indigo-500 rounded-full inline-block animate-pulse"></span>
                             @endif
                             <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-700 transition-colors">{{ $notif->table->name ?? 'Meja ?' }}</p>
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                    <p class="text-xs text-slate-500 truncate pl-4">
                        {{ $notif->customer_name }} memesan {{ $notif->items_count }} menu...
                    </p>
                </a>
            @empty
                <div class="py-8 text-center px-4">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 mb-2">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <p class="text-xs text-slate-400 font-bold">Semua pesanan aman terkendali.</p>
                </div>
            @endforelse
        </div>

        <div class="bg-slate-50 p-2 text-center border-t border-slate-100">
            <a href="{{ route('admin.kitchen') }}" wire:navigate class="block w-full py-2 text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors">
                Lihat Semua Pesanan &rarr;
            </a>
        </div>
    </div>
</div>