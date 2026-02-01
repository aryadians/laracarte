<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-black text-slate-800">Manajemen Bahan Baku 🥦</h1>
        <button wire:click="create" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg">
            + Bahan Baru
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-6">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari bahan (Beras, Telur...)" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500">
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Nama Bahan</th>
                    <th class="px-6 py-4">Unit</th>
                    <th class="px-6 py-4">Stok Saat Ini</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($ingredients as $ing)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $ing->name }}</td>
                    <td class="px-6 py-4">{{ $ing->unit }}</td>
                    <td class="px-6 py-4">
                        <span class="font-black {{ $ing->stock <= $ing->min_stock ? 'text-red-600' : 'text-slate-700' }}">
                            {{ number_format($ing->stock, 0) }} {{ $ing->unit }}
                        </span>
                        @if($ing->stock <= $ing->min_stock)
                            <span class="ml-2 bg-red-100 text-red-600 text-[10px] px-2 py-0.5 rounded-full font-bold">Hampir Habis!</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button wire:click="edit({{ $ing->id }})" class="text-indigo-600 font-bold mr-3">Edit</button>
                        <button wire:confirm="Hapus bahan ini?" wire:click="delete({{ $ing->id }})" class="text-red-600 font-bold">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada data bahan baku.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-50">
            {{ $ingredients->links() }}
        </div>
    </div>

    {{-- MODAL CRUD --}}
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-black text-slate-800">{{ $isEditMode ? 'Edit Bahan' : 'Tambah Bahan' }}</h3>
                <button wire:click="$set('isOpen', false)" class="text-slate-400">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Bahan</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Satuan/Unit</label>
                        <input type="text" wire:model="unit" class="w-full rounded-xl border-slate-200" placeholder="gram, ml, butir">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Min. Alert</label>
                        <input type="number" wire:model="min_stock" class="w-full rounded-xl border-slate-200">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Stok Awal</label>
                    <input type="number" wire:model="stock" class="w-full rounded-xl border-slate-200 font-bold">
                </div>
            </div>
            <div class="p-6 border-t border-slate-100 flex justify-end gap-3">
                <button wire:click="$set('isOpen', false)" class="px-4 py-2 text-slate-500 font-bold">Batal</button>
                <button wire:click="{{ $isEditMode ? 'update' : 'store' }}" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold">Simpan</button>
            </div>
        </div>
    </div>
    @endif
</div>