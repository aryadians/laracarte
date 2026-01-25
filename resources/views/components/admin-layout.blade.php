<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LaraCarte') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        /* Custom Scrollbar untuk Sidebar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">

    <aside class="fixed top-0 left-0 z-50 w-72 h-screen bg-[#0B1120] text-white border-r border-slate-800/50 flex flex-col transition-transform duration-300 transform -translate-x-full sm:translate-x-0 shadow-2xl">
        
        <div class="h-24 flex items-center px-8 border-b border-slate-800/50 bg-[#0B1120] shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 ring-1 ring-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A8 8 0 0 1 12 10a8 8 0 0 1 6 3.88"></path><path d="M2 14h20"></path><path d="M12 10v-3"></path><path d="M12 4a2 2 0 1 1 0 4 2 2 0 0 1 0-4Z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tight text-white leading-none">Lara<span class="text-indigo-400">Carte.</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.2em] uppercase mt-1.5">Resto Manager</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-8 px-5 space-y-10 custom-scrollbar">
            
            <div class="space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="font-bold text-sm tracking-wide">Dashboard</span>
                </a>
            </div>

            <div>
                <p class="px-4 text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-4">Master Data</p>
                <div class="space-y-2">
                    <a href="{{ Route::has('admin.products') ? route('admin.products') : route('products') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('products') || request()->routeIs('admin.products') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('products') || request()->routeIs('admin.products') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span class="font-semibold text-sm">Produk & Menu</span>
                    </a>

                    <a href="{{ Route::has('admin.tables') ? route('admin.tables') : route('tables') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('tables') || request()->routeIs('admin.tables') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('tables') || request()->routeIs('admin.tables') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="font-semibold text-sm">Manajemen Meja</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-4 text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-4">Operasional</p>
                <div class="space-y-2">
                    <a href="{{ Route::has('admin.orders') ? route('admin.orders') : '#' }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.orders') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.orders') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        <span class="font-semibold text-sm">Dapur (Orders)</span>
                    </a>

                    <a href="{{ Route::has('admin.history') ? route('admin.history') : route('history') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('history') || request()->routeIs('admin.history') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('history') || request()->routeIs('admin.history') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span class="font-semibold text-sm">Riwayat Transaksi</span>
                    </a>

                    <a href="{{ Route::has('orders') ? route('orders') : '#' }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('orders') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                         <svg class="w-5 h-5 {{ request()->routeIs('orders') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="font-semibold text-sm">Pesanan Masuk</span>
                    </a>
                </div>
            </div>

        </nav>

        <div class="p-5 border-t border-slate-800/50 bg-[#0F1623] shrink-0">
            <div class="flex items-center gap-3.5 mb-5 px-1">
                <div class="relative">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-sm ring-2 ring-slate-800 shadow-lg">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#0F1623] rounded-full"></div>
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-sm font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</h4>
                    <p class="text-[11px] font-semibold text-slate-500 truncate uppercase tracking-wide">Administrator</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2.5 py-3.5 rounded-xl bg-slate-800/50 hover:bg-red-500/10 hover:text-red-400 text-slate-400 font-bold text-[11px] uppercase tracking-wider transition-all border border-slate-700 hover:border-red-500/30 group">
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="sm:ml-72 min-h-screen flex flex-col transition-all duration-300">
        
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/60 flex items-center justify-between px-8 z-40 sticky top-0">
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                {{ $header ?? 'Halaman Admin' }}
            </h2>
            <button class="p-2 text-slate-400 hover:text-indigo-600 transition-colors relative bg-white rounded-full shadow-sm border border-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
            </button>
        </header>
        
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>