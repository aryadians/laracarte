<x-admin-layout>
    <x-slot name="header">Overview Bisnis</x-slot>

    <div class="relative w-full p-8 mb-10 overflow-hidden rounded-3xl bg-slate-900 shadow-2xl shadow-indigo-900/20 text-white group">
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/2 w-96 h-96 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-4xl font-black tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-white via-indigo-200 to-indigo-400">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-slate-400 text-lg max-w-xl">
                    Selamat datang kembali di panel admin LaraCarte. Hari ini sepertinya waktu yang tepat untuk meningkatkan penjualan!
                </p>
            </div>
            <div class="hidden md:block opacity-80 transform group-hover:scale-110 transition-transform duration-700">
                <svg class="w-32 h-32 text-indigo-500" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-2 transition-all duration-500 group overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:rotate-12">
                 <svg class="w-24 h-24 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z"/></svg>
            </div>
            
            <div class="relative z-10">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Total Pesanan</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-5xl font-black text-slate-800">24</h3>
                    <span class="mb-2 px-2 py-1 text-xs font-bold text-green-600 bg-green-100 rounded-lg flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        +12%
                    </span>
                </div>
            </div>
            <div class="mt-4 w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 w-3/4 rounded-full"></div>
            </div>
        </div>

        <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-2 transition-all duration-500 group overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:rotate-12">
                 <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01" /></svg>
            </div>
            
            <div class="relative z-10">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Pendapatan</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-5xl font-black text-slate-800">1.2<span class="text-2xl text-slate-500">Jt</span></h3>
                    <span class="mb-2 px-2 py-1 text-xs font-bold text-emerald-600 bg-emerald-100 rounded-lg flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Aman
                    </span>
                </div>
            </div>
             <div class="mt-4 w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 w-full rounded-full"></div>
            </div>
        </div>

        <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-orange-500/10 hover:-translate-y-2 transition-all duration-500 group overflow-hidden">
             <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:rotate-12">
                 <svg class="w-24 h-24 text-orange-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>

            <div class="relative z-10">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Meja Terisi</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-5xl font-black text-slate-800">3 <span class="text-2xl text-slate-400 font-medium">/ 10</span></h3>
                </div>
            </div>
             <div class="mt-4 w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-orange-500 w-1/3 rounded-full"></div>
            </div>
        </div>
    </div>
</x-admin-layout>