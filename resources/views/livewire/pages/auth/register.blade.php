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

layout('layouts.guest');

state([
    'name' => '',
    'store_name' => '', // NEW
    'email' => '',
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'store_name' => ['required', 'string', 'max:255', 'unique:' . Tenant::class . ',name'], // NEW
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

<div
    class="fixed inset-0 z-50 min-h-screen bg-slate-50 flex items-center justify-center overflow-hidden font-sans text-slate-600">

    <div id="vanta-bg-register" class="absolute inset-0 z-0"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js" defer></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            if (window.VANTA) {
                VANTA.WAVES({
                    el: "#vanta-bg-register",
                    mouseControls: true,
                    touchControls: true,
                    gyroControls: false,
                    minHeight: 200.00,
                    minWidth: 200.00,
                    scale: 1.00,
                    scaleMobile: 1.00,
                    color: 0x4f46e5, // indigo-600
                    shininess: 30.00,
                    waveHeight: 20.00,
                    waveSpeed: 0.7,
                    zoom: 0.75
                })
            }
        })
    </script>

    <div class="absolute inset-0 w-full h-full pointer-events-none bg-slate-50/10"></div>

    <!-- Main Card Container -->
    <div class="relative w-full max-w-[500px] h-[90vh] md:h-auto md:max-h-[90vh] flex flex-col">

        <!-- Logo & Header -->
        <div class="text-center mb-6 animate-fade-in-down shrink-0">
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 shadow-xl shadow-indigo-500/30 mb-4 transform transition hover:scale-105 duration-300">
                <svg width="32" height="32" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M256 130C265.9 130 274 121.9 274 112C274 102.1 265.9 94 256 94C246.1 94 238 102.1 238 112C238 121.9 246.1 130 256 130Z"
                        fill="white" />
                    <path d="M256 148C176.5 148 108.8 198.7 82 270H430C403.2 198.7 335.5 148 256 148Z" fill="white" />
                    <path d="M70 306V324C70 343.9 86.1 360 106 360H406C425.9 360 442 343.9 442 324V306H70Z"
                        fill="white" />
                </svg>
            </div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Daftar <span
                    class="text-indigo-600">Merchant</span></h2>
            <p class="text-slate-500 text-xs font-medium mt-1 tracking-wide">Mulai kelola restoranmu dengan LaraCarte
            </p>
        </div>

        <!-- Scrollable Form Container -->
        <div
            class="bg-white/90 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-2xl shadow-slate-200/50 flex-1 overflow-hidden flex flex-col relative">

            <div class="overflow-y-auto p-8 sm:p-10 no-scrollbar custom-scrollbar">
                <form wire:submit="register" class="flex flex-col gap-5">

                    <!-- Name -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1"
                            for="name">
                            Nama Lengkap
                        </label>
                        <input wire:model="name" id="name"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 rounded-xl text-slate-700 font-bold placeholder-slate-400 text-sm transition-all outline-none"
                            type="text" required autofocus placeholder="John Doe" />
                        @error('name') <p class="text-red-500 text-xs font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Store Name -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1"
                            for="store_name">
                            Nama Restoran / Toko
                        </label>
                        <div class="relative group">
                            <input wire:model="store_name" id="store_name"
                                class="w-full pl-10 px-4 py-3 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 rounded-xl text-slate-700 font-bold placeholder-slate-400 text-sm transition-all outline-none"
                                type="text" required placeholder="My Awesome Cafe" />
                            <div
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                        @error('store_name') <p class="text-red-500 text-xs font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1"
                            for="email">
                            Email Address
                        </label>
                        <div class="relative group">
                            <input wire:model="email" id="email"
                                class="w-full pl-10 px-4 py-3 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 rounded-xl text-slate-700 font-bold placeholder-slate-400 text-sm transition-all outline-none"
                                type="email" required placeholder="email@example.com" />
                            <div
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                        </div>
                        @error('email') <p class="text-red-500 text-xs font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Password -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1"
                                for="password">
                                Password
                            </label>
                            <input wire:model="password" id="password"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 rounded-xl text-slate-700 font-bold placeholder-slate-400 text-sm transition-all outline-none"
                                type="password" required placeholder="••••••••" />
                            @error('password') <p class="text-red-500 text-xs font-bold ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1"
                                for="password_confirmation">
                                Konfirmasi
                            </label>
                            <input wire:model="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 rounded-xl text-slate-700 font-bold placeholder-slate-400 text-sm transition-all outline-none"
                                type="password" required placeholder="••••••••" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            class="w-full py-3.5 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/40 transform transition-all duration-300 hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 group">
                            <span wire:loading.remove>DAFTAR SEKARANG</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <svg wire:loading.remove class="w-5 h-5 group-hover:translate-x-1 transition-transform"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>

                    <div class="text-center">
                        <a class="text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors"
                            href="{{ route('login') }}">
                            Sudah punya akun? Masuk
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <p class="text-center text-slate-400 text-[10px] font-bold mt-6 tracking-widest uppercase opacity-60">
            © 2026 LaraCarte System.
        </p>
    </div>

    <style>
        /* Custom Scrollbar for nicer form feeling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.3);
            border-radius: 20px;
        }

        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
        }
    </style>
</div>