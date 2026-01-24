<x-admin-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="text-gray-500 text-sm">Total Pesanan Hari Ini</div>
            <div class="text-3xl font-bold text-gray-800 mt-2">0</div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="text-gray-500 text-sm">Pendapatan Hari Ini</div>
            <div class="text-3xl font-bold text-green-600 mt-2">Rp 0</div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="text-gray-500 text-sm">Meja Terisi</div>
            <div class="text-3xl font-bold text-blue-600 mt-2">0 / 5</div>
        </div>
    </div>
    
    <div class="mt-8 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold mb-4">Aktivitas Terbaru</h3>
        <p class="text-gray-500">Belum ada pesanan masuk.</p>
    </div>
</x-admin-layout>