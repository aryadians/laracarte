<?php

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Validation\Rules;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

new #[Layout('components.admin-layout')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $role = UserRole::CASHIER->value;
    public string $password = '';
    public string $password_confirmation = '';

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Tenant ID is automatically handled by BelongsToTenant trait/scope if we use User::create
        // But since User model has the trait, and we are logged in, it *should* work.
        // However, BelongsToTenant::creating uses auth()->user()->tenant_id.
        
        auth()->user()->tenant->users()->create($validated);

        $this->redirect(route('employees.index'), navigate: true);
    }
    
    public function with(): array
    {
        return [
            'roles' => UserRole::cases(),
        ];
    }
}; ?>

<div class="p-6">
    <div class="max-w-3xl mx-auto">
        
        <div class="mb-8">
            <a href="{{ route('employees.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Karyawan
            </a>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Tambah Karyawan Baru 👤</h1>
            <p class="text-slate-500 mt-1 text-lg">Buat akun untuk staf restoran Anda agar mereka bisa mengakses sistem.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
            
            <div class="p-8">
                <form wire:submit="save" class="space-y-6">
                    <!-- Name & Email Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                            <input wire:model="name" type="text" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-300 font-medium" placeholder="Contoh: Budi Santoso" required autofocus>
                            @error('name') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Email</label>
                            <input wire:model="email" type="email" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-300 font-medium" placeholder="budi@example.com" required>
                            @error('email') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <!-- Role Selection -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Peran & Akses</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($roles as $r)
                                @if($r !== \App\Enums\UserRole::OWNER && $r !== \App\Enums\UserRole::CUSTOMER)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" wire:model="role" value="{{ $r->value }}" class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-indigo-500 peer-checked:bg-indigo-50/50 transition-all hover:border-slate-300">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 peer-checked:bg-indigo-100 peer-checked:text-indigo-600 transition-colors">
                                                @if($r === \App\Enums\UserRole::CASHIER)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2-2v14a2 2 0 002 2z"></path></svg>
                                                @elseif($r === \App\Enums\UserRole::KITCHEN)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="block font-bold text-slate-700 peer-checked:text-indigo-700">{{ $r->label() }}</span>
                                                <span class="block text-xs text-slate-500">
                                                    @if($r === \App\Enums\UserRole::CASHIER) Akses mesin kasir & laporan.
                                                    @elseif($r === \App\Enums\UserRole::KITCHEN) Akses layar pesanan dapur.
                                                    @else Akses pemesanan meja.
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                @endif
                            @endforeach
                        </div>
                        @error('role') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Password Login</label>
                            <input wire:model="password" type="password" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-300 font-medium" required>
                            @error('password') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Konfirmasi Password</label>
                            <input wire:model="password_confirmation" type="password" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-300 font-medium" required>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4 pt-6 mt-4 border-t border-slate-100">
                        <a href="{{ route('employees.index') }}" class="px-6 py-3 text-slate-600 font-bold hover:bg-slate-100 rounded-xl text-sm transition-colors">Batal</a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 flex items-center gap-2">
                            <span wire:loading.remove>Simpan Karyawan</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
