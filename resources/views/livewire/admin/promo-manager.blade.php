<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-black text-slate-800">Promo & Diskon 🏷️</h1>
        <button wire:click="create" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg">
            + Promo Baru
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Nama Promo</th>
                    <th class="px-6 py-4">Tipe</th>
                    <th class="px-6 py-4">Nilai</th>
                    <th class="px-6 py-4">Min. Belanja</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($promos as $promo)
                <tr class="hover:bg-slate-50 transition-colors {{ !$promo->is_active ? 'opacity-50' : '' }}">
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $promo->name }}</td>
                    <td class="px-6 py-4 uppercase text-xs font-bold text-slate-400">{{ $promo->type }}</td>
                    <td class="px-6 py-4 font-black text-indigo-600">
                        {{ $promo->type == 'percentage' ? $promo->value . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-slate-500">Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        <button wire:click="toggleStatus({{ $promo->id }})" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $promo->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $promo->is_active ? 'Aktif' : 'Non-aktif' }}
                        </button>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button wire:click="edit({{ $promo->id }})" class="text-indigo-600 font-bold mr-3">Edit</button>
                        <button wire:confirm="Hapus promo ini?" wire:click="delete({{ $promo->id }})" class="text-red-600 font-bold">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada promo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL CRUD --}}
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-black text-slate-800">{{ $isEditMode ? 'Edit Promo' : 'Tambah Promo' }}</h3>
                <button wire:click="$set('isOpen', false)" class="text-slate-400">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Promo</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200" placeholder="Misal: Diskon Merdeka">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipe</label>
                        <select wire:model="type" class="w-full rounded-xl border-slate-200">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nilai Diskon</label>
                        <input type="number" wire:model="value" class="w-full rounded-xl border-slate-200 font-bold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Minimal Belanja (Rp)</label>
                    <input type="number" wire:model="min_purchase" class="w-full rounded-xl border-slate-200">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="is_active" id="is_active" class="rounded text-indigo-600">
                    <label for="is_active" class="text-sm font-bold text-slate-700">Promo Aktif</label>
                </div>
            </div>
            <div class="p-6 border-t border-slate-100 flex justify-end gap-3">
                <button wire:click="$set('isOpen', false)" class="px-4 py-2 text-slate-500 font-bold">Batal</button>
                <button wire:click="{{ $isEditMode ? 'update' : 'store' }}" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold shadow-lg">Simpan</button>
            </div>
        </div>
    </div>
    @endif
</div>