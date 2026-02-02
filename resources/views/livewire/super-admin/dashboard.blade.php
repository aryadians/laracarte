<div class="space-y-8">
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Tenants --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-md transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Tenant</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalTenants }}</h3>
            </div>
        </div>

        {{-- Total Users --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-md transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalUsers }}</h3>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-md transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pesanan</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalOrders }}</h3>
            </div>
        </div>

        {{-- Global Revenue --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-md transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Omzet Global</p>
                <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- Info Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Platform Health --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Status Platform
                </h3>
            </div>
            <div class="p-8 space-y-6">
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <span class="text-sm font-medium text-slate-600">Database Connection</span>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase italic">Stable</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <span class="text-sm font-medium text-slate-600">Storage Usage</span>
                    <span class="text-sm font-bold text-slate-800">Low (2.4 GB)</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 border border-slate-100 rounded-2xl">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Laravel Version</p>
                        <p class="text-lg font-black text-slate-700">v12.0</p>
                    </div>
                    <div class="p-4 border border-slate-100 rounded-2xl">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">PHP Version</p>
                        <p class="text-lg font-black text-slate-700">v8.2.x</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-indigo-900 rounded-3xl shadow-sm p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xl font-black mb-2">Pusat Kendali Platform</h3>
                <p class="text-indigo-200 text-sm mb-8 max-w-xs">Kelola semua tenant dan monitoring aktivitas sistem dalam satu panel kendali.</p>
                
                <div class="space-y-4">
                    <a wire:navigate href="{{ route('super-admin.tenants') }}" class="w-full py-4 bg-white/10 hover:bg-white/20 rounded-2xl text-left px-6 border border-white/10 transition-all group flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <span class="font-bold">Manajemen Restoran</span>
                        </div>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    
                    <a href="#" class="w-full py-4 bg-white/10 hover:bg-white/20 rounded-2xl text-left px-6 border border-white/10 transition-all group flex items-center justify-between opacity-50 cursor-not-allowed">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="font-bold">Pengaturan Platform</span>
                        </div>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            {{-- Decor --}}
            <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-10 -right-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl"></div>
        </div>
    </div>
</div>
