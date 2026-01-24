<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LaraCarte') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">
    
    <div class="flex h-screen overflow-hidden w-full">
        
        <aside class="w-72 bg-slate-900 flex flex-col shadow-2xl z-50 relative hidden md:flex">
            <div class="h-24 flex items-center justify-center border-b border-slate-800/50 bg-slate-900 gap-3 px-4">
                <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg shadow-indigo-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-wider text-white leading-none">
                        Lara<span class="text-indigo-500">Carte</span>
                    </h1>
                    <p class="text-[0.6rem] text-slate-400 tracking-widest uppercase mt-1">Resto Management</p>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
                <x-nav-link-admin href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
                    Dashboard
                </x-nav-link-admin>

                <div class="text-[0.65rem] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-3 px-4">Master Data</div>
                
                <x-nav-link-admin href="{{ route('products') }}" :active="request()->routeIs('products')" icon="cube">
                    Produk & Menu
                </x-nav-link-admin>

                <x-nav-link-admin href="#" icon="table">
                    Manajemen Meja
                </x-nav-link-admin>

                <div class="text-[0.65rem] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-3 px-4">Kasir</div>
                
                <x-nav-link-admin href="{{ route('orders') }}" :active="request()->routeIs('orders')" icon="receipt">
    Pesanan Masuk
</x-nav-link-admin>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-900">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-slate-800">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400">Administrator</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center py-2.5 text-sm font-semibold text-red-400 bg-red-500/10 rounded-lg hover:bg-red-500 hover:text-white transition-all duration-300 group">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar Aplikasi
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden relative bg-slate-50">
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
    </div>
</body>
</html>