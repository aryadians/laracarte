<?php

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.auth-split');

state([
    'name' => '',
    'store_name' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'store_name' => ['required', 'string', 'max:255', 'unique:' . Tenant::class . ',name'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$register = function () {
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    // 1. Create Tenant
    $tenant = Tenant::create([
        'name' => $validated['store_name'],
        'slug' => Str::slug($validated['store_name']) . '-' . Str::random(4),
    ]);

    // 2. Create User linked to Tenant
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => $validated['password'],
        'role' => 'admin', // First user is admin
        'tenant_id' => $tenant->id,
    ]);

    event(new Registered($user));

    Auth::login($user);

    $this->redirect(route('dashboard', absolute: false));
};

?>

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-white font-sans text-slate-600">

    <!-- Left Side: Brand & Visuals (Desktop Only) -->
    <div class="hidden lg:flex relative items-center justify-center overflow-hidden bg-slate-900">
        <div class="absolute inset-0 bg-gradient-to-tr from-purple-900 via-slate-900 to-black z-10 opacity-95"></div>
        <img src="https://images.unsplash.com/photo-1514362545857-3bc16549766b?q=80&w=2070&auto=format&fit=crop" 
             alt="Restaurant Kitchen" 
             class="absolute inset-0 w-full h-full object-cover">
        
        <div class="relative z-20 max-w-xl p-16">
            <h2 class="text-5xl font-black text-white tracking-tight mb-8 leading-tight">Start Your<br>Culinary Journey.</h2>
             <ul class="space-y-6 text-slate-300 font-medium text-lg">
                <li class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center ring-1 ring-green-500/30">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span>Manajemen KDS (Kitchen Display System)</span>
                </li>
                <li class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center ring-1 ring-green-500/30">
                         <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span>Laporan Keuangan Real-time</span>
                </li>
                <li class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center ring-1 ring-green-500/30">
                         <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span>Sistem Inventori Bahan Baku</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Right Side: Register Form -->
    <div class="flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white overflow-y-auto">
        <div class="w-full max-w-md space-y-8">
            
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center gap-2 mb-4 lg:mb-8">
                     <div class="w-10 h-10 rounded-xl bg-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-600/20">
                         <svg class="w-5 h-5" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M256 130C265.9 130 274 121.9 274 112C274 102.1 265.9 94 256 94C246.1 94 238 102.1 238 112C238 121.9 246.1 130 256 130Z" fill="currentColor" />
                            <path d="M256 148C176.5 148 108.8 198.7 82 270H430C403.2 198.7 335.5 148 256 148Z" fill="currentColor" />
                            <path d="M70 306V324C70 343.9 86.1 360 106 360H406C425.9 360 442 343.9 442 324V306H70Z" fill="currentColor" />
                        </svg>
                    </div>
                    <span class="text-2xl font-black text-slate-900 tracking-tighter">LaraCarte.</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Buat Akun Baru</h1>
                <p class="text-slate-500 mt-2">Daftar sekarang, gratis 14 hari percobaan.</p>
            </div>

            <form wire:submit="register" class="space-y-6">
                
                <!-- Personal Info Group -->
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Informasi Pemilik</h3>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-900">Nama Lengkap</label>
                        <input wire:model="name" type="text" required autofocus placeholder="John Doe"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 transition-all font-medium text-slate-900 placeholder:text-slate-400" />
                        @error('name') <p class="text-red-500 text-sm font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-900">Email Address</label>
                        <input wire:model="email" type="email" required placeholder="email@bisnis.com"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 transition-all font-medium text-slate-900 placeholder:text-slate-400" />
                        @error('email') <p class="text-red-500 text-sm font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Store Info Group -->
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Informasi Restoran</h3>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-900">Nama Restoran / Cafe</label>
                        <input wire:model="store_name" type="text" required placeholder="Kopi Kenangan Mantan"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 transition-all font-medium text-slate-900 placeholder:text-slate-400" />
                        @error('store_name') <p class="text-red-500 text-sm font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Security Group -->
                 <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-900">Password</label>
                        <input wire:model="password" type="password" required placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 transition-all font-medium text-slate-900 placeholder:text-slate-400" />
                         @error('password') <p class="text-red-500 text-sm font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-900">Konfirmasi</label>
                        <input wire:model="password_confirmation" type="password" required placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 transition-all font-medium text-slate-900 placeholder:text-slate-400" />
                    </div>
                </div>

                <div class="flex items-start gap-3 bg-slate-50 p-4 rounded-xl">
                    <input type="checkbox" required class="mt-1 rounded border-slate-300 text-purple-600 focus:ring-purple-500 cursor-pointer w-4 h-4">
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Dengan mendaftar, Anda menyetujui <a href="#" class="text-purple-600 font-bold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-purple-600 font-bold hover:underline">Kebijakan Privasi</a> kami.
                    </p>
                </div>

                <button type="submit" 
                    class="w-full py-3.5 px-6 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-600/20 transform transition-all duration-200 hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                    <span wire:loading.remove>Buat Akun Merchant &rarr;</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </form>

            <div class="pt-8 text-center border-t border-slate-100">
                <p class="text-slate-500 text-sm mb-4">Sudah punya akun?</p>
                <a href="{{ route('login') }}" 
                    class="inline-block px-8 py-3 rounded-xl bg-slate-50 text-slate-900 border border-slate-200 text-sm font-bold hover:bg-purple-50 hover:text-purple-700 hover:border-purple-200 transition-all">
                    Masuk ke Dashboard
                </a>
            </div>
             <div class="mt-8 text-center">
                 <p class="text-xs text-slate-400 font-medium">
                    © 2026 LaraCarte System. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>