<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LaraCarte Admin') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <aside class="w-full md:w-64 bg-white border-r border-gray-200 md:min-h-screen">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <span class="text-xl font-bold text-indigo-600">LaraCarte</span>
                </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="block p-2 rounded hover:bg-indigo-50 text-gray-700 font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    📊 Dashboard
                </a>
                <a href="#" class="block p-2 rounded hover:bg-indigo-50 text-gray-700 font-medium">
                    🍔 Produk & Menu
                </a>
                <a href="#" class="block p-2 rounded hover:bg-indigo-50 text-gray-700 font-medium">
                    🪑 Manajemen Meja
                </a>
                <a href="#" class="block p-2 rounded hover:bg-indigo-50 text-gray-700 font-medium">
                    🧾 Pesanan Masuk
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-8 border-t pt-4">
                    @csrf
                    <button type="submit" class="w-full text-left p-2 rounded text-red-600 hover:bg-red-50 font-medium">
                        🚪 Logout
                    </button>
                </form>
            </nav>
        </aside>

        <main class="flex-1 p-6">
            @if (isset($header))
                <header class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $header }}
                    </h2>
                </header>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>