<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>LaraCarte</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar untuk Sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0f172a;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-800">

@php
    $isSuperAdmin = auth()->user()->hasRole(\App\Enums\UserRole::SUPER_ADMIN);
@endphp
    <aside class="fixed top-0 left-0 z-50 w-72 h-screen bg-[#0B1120] text-white border-r border-slate-800/50 flex flex-col transition-transform duration-300 transform -translate-x-full sm:translate-x-0 shadow-2xl">

        <div class="h-24 flex items-center px-8 border-b border-slate-800/50 bg-[#0B1120] shrink-0">
            <div class="flex items-center gap-3.5">
                @if($isSuperAdmin)
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-orange-600 flex items-center justify-center shadow-lg shadow-rose-500/20 ring-1 ring-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                @elseif(auth()->user()->tenant && auth()->user()->tenant->logo)
                    <img src="{{ asset('storage/' . auth()->user()->tenant->logo) }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-white/10 shadow-lg shadow-indigo-500/20">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 ring-1 ring-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 13.87A8 8 0 0 1 12 10a8 8 0 0 1 6 3.88"></path>
                            <path d="M2 14h20"></path>
                            <path d="M12 10v-3"></path>
                            <path d="M12 4a2 2 0 1 1 0 4 2 2 0 0 1 0-4Z"></path>
                        </svg>
                    </div>
                @endif
                <div>
                    <h1 class="text-xl font-black tracking-tight text-white leading-none truncate max-w-[140px]">
                        @if($isSuperAdmin)
                            Platform Admin
                        @else
                            {{ auth()->user()->tenant->name ?? 'LaraCarte' }}
                        @endif
                    </h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.2em] uppercase mt-1.5">{{ $isSuperAdmin ? 'Central Command' : 'Resto Manager' }}</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-8 px-5 space-y-10 custom-scrollbar">

            @if($isSuperAdmin)
                {{-- Super Admin Navigation --}}
                <div class="space-y-2">
                    <a wire:navigate href="{{ route('super-admin.dashboard') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('super-admin.dashboard') ? 'bg-rose-600 text-white shadow-lg shadow-rose-500/25 ring-1 ring-rose-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('super-admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span class="font-bold text-sm tracking-wide">Pusat Statistik</span>
                    </a>
                </div>

                <div>
                    <p class="px-4 text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-4">Platform Control</p>
                    <div class="space-y-2">
                        <a wire:navigate href="{{ route('super-admin.tenants') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('super-admin.tenants') ? 'bg-rose-600 text-white shadow-lg shadow-rose-500/25 ring-1 ring-rose-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('super-admin.tenants') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="font-bold text-sm">Manajemen Restoran</span>
                        </a>
                        <a href="#" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group text-slate-400 hover:bg-slate-800/50 hover:text-white opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5 text-slate-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-bold text-sm">Keuangan Platform</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-2">
                    <a wire:navigate href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span class="font-bold text-sm tracking-wide">Dashboard</span>
                    </a>
                    @if(auth()->user()->hasRole(\App\Enums\UserRole::OWNER))
                    <a wire:navigate href="{{ route('admin.reports') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.reports') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.reports') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-bold text-sm tracking-wide">Laporan Analitik</span>
                    </a>
                @endif
                </div>

            <div>
                <p class="px-4 text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-4">Master Data</p>
                <div class="space-y-2">
                    @if(auth()->user()->hasRole(\App\Enums\UserRole::OWNER))
                    <a wire:navigate href="{{ route('employees.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('employees.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('employees.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-bold text-sm">Manajemen Karyawan</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole(\App\Enums\UserRole::OWNER))
                    <a wire:navigate href="{{ route('admin.products') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.products') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.products') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="font-bold text-sm">Produk & Menu</span>
                    </a>

                    <a wire:navigate href="{{ route('admin.ingredients') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.ingredients') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.ingredients') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="font-bold text-sm">Bahan Baku</span>
                    </a>

                    <a wire:navigate href="{{ route('admin.promos') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.promos') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.promos') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <span class="font-bold text-sm">Promo & Diskon</span>
                    </a>
                    @endif

                    <a wire:navigate href="{{ route('admin.tables') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.tables') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.tables') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="font-bold text-sm">Manajemen Meja</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-4 text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-4">Operasional</p>
                <div class="space-y-2">
                    @if(auth()->user()->hasRole(\App\Enums\UserRole::KITCHEN) || auth()->user()->hasRole(\App\Enums\UserRole::OWNER))
                    <a wire:navigate href="{{ route('admin.kitchen') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.kitchen') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.kitchen') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <span class="font-bold text-sm">Dapur (KDS)</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole(\App\Enums\UserRole::KITCHEN) || auth()->user()->hasRole(\App\Enums\UserRole::WAITER) || auth()->user()->hasRole(\App\Enums\UserRole::OWNER))
                    <a wire:navigate href="{{ route('admin.expo') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.expo') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.expo') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="font-bold text-sm">Expo / Runner</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole(\App\Enums\UserRole::CASHIER) || auth()->user()->hasRole(\App\Enums\UserRole::OWNER))
                    <a wire:navigate href="{{ route('admin.cashier') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.cashier') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.cashier') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2-2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-bold text-sm">Kasir (POS)</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole(\App\Enums\UserRole::CASHIER) || auth()->user()->hasRole(\App\Enums\UserRole::OWNER))
                    <a wire:navigate href="{{ route('admin.history') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.history') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.history') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span class="font-bold text-sm">Riwayat Transaksi</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole(\App\Enums\UserRole::OWNER))
                    <a wire:navigate href="{{ route('admin.transactions') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.transactions') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.transactions') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-bold text-sm">Laporan Harian</span>
                    </a>

                    <a wire:navigate href="{{ route('admin.settings') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.settings') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 ring-1 ring-indigo-400/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.settings') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="font-bold text-sm">Pengaturan</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif
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
                    <p class="text-[11px] font-semibold text-slate-500 truncate uppercase tracking-wide">
                        @php
                            $user = Auth::user();
                            $roleLabel = 'Administrator';
                            if ($isSuperAdmin) {
                                $roleLabel = 'Platform Admin';
                            } elseif ($user && method_exists($user->role, 'label')) {
                                $roleLabel = $user->role->label();
                            } elseif ($user && is_object($user->role)) {
                                $roleLabel = $user->role->value ?? 'Staff';
                            }
                        @endphp
                        {{ $roleLabel }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2.5 py-3.5 rounded-xl bg-slate-800/50 hover:bg-red-500/10 hover:text-red-400 text-slate-400 font-bold text-[11px] uppercase tracking-wider transition-all border border-slate-700 hover:border-red-500/30 group">
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="sm:ml-72 min-h-screen flex flex-col transition-all duration-300">

        @if(session()->has('impersonator_id'))
            <div class="bg-rose-600 text-white px-8 py-3 flex items-center justify-between shadow-lg relative z-[60]">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center animate-pulse">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center gap-1 md:gap-3">
                        <span class="text-sm font-black tracking-wide uppercase italic">Mode Impersonasi:</span>
                        <span class="text-sm font-bold opacity-90">Anda masuk sebagai Owner <strong>{{ auth()->user()->name }}</strong></span>
                    </div>
                </div>
                <form action="{{ route('impersonate.leave') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-white text-rose-600 px-5 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider hover:bg-rose-50 transition-all shadow-sm">
                        Balik ke Super Admin
                    </button>
                </form>
            </div>
        @endif

        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/60 flex items-center justify-between px-8 z-40 sticky top-0">
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                {{ $header ?? 'Halaman Admin' }}
            </h2>

            <livewire:admin.notification-bell />
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {
                    if (status === 419) {
                        // Jika session expired, refresh halaman otomatis 
                        // agar redirect ke login tanpa muncul popup error
                        window.location.reload();
                        preventDefault();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>