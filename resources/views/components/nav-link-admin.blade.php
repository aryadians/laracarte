@props(['active' => false, 'icon' => 'home', 'href' => '#'])

@php
    $baseClass = "group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 mx-2 mb-1";
    
    // Jika Aktif: Gradient Background + Shadow
    $activeClass = "bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30 scale-105";
    
    // Jika Tidak Aktif: Transparent + Hover Effect
    $inactiveClass = "text-slate-400 hover:bg-white/10 hover:text-white hover:translate-x-1";
    
    // Ikon Mapping
    $icons = [
        'home' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>', // Dashboard Grid Icon
        'cube' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
        'table' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>',
        'receipt' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
    ];
@endphp

<a href="{{ $href }}" class="{{ $baseClass }} {{ $active ? $activeClass : $inactiveClass }}">
    <svg class="w-5 h-5 mr-3 transition-colors duration-300 {{ $active ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icons[$icon] ?? $icons['home'] !!}
    </svg>
    <span>{{ $slot }}</span>
</a>