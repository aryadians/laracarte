<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.auth-split');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $user = auth()->user();
    $redirect = route('dashboard', absolute: false);

    if ($user->hasRole(\App\Enums\UserRole::CASHIER)) {
        $redirect = route('admin.cashier', absolute: false);
    } elseif ($user->hasRole(\App\Enums\UserRole::KITCHEN)) {
        $redirect = route('admin.kitchen', absolute: false);
    } elseif ($user->hasRole(\App\Enums\UserRole::WAITER)) {
        $redirect = route('admin.tables', absolute: false);
    }

    $this->redirectIntended(default: $redirect);
};

?>

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-white font-sans text-slate-600">
    
    <!-- Left Side: Brand & Visuals (Desktop Only) -->
    <div class="hidden lg:flex relative items-center justify-center overflow-hidden bg-slate-900">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-slate-900 to-black z-10 opacity-90"></div>
        <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?q=80&w=2070&auto=format&fit=crop" 
             alt="Restaurant Interior" 
             class="absolute inset-0 w-full h-full object-cover">
        
        <div class="relative z-20 max-w-xl text-center p-16">
            <div class="mb-8 flex justify-center">
                <div class="w-24 h-24 rounded-3xl bg-white/5 backdrop-blur-md border border-white/10 flex items-center justify-center shadow-2xl">
                   <svg class="w-12 h-12 text-white" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M256 130C265.9 130 274 121.9 274 112C274 102.1 265.9 94 256 94C246.1 94 238 102.1 238 112C238 121.9 246.1 130 256 130Z" fill="currentColor" />
                        <path d="M256 148C176.5 148 108.8 198.7 82 270H430C403.2 198.7 335.5 148 256 148Z" fill="currentColor" />
                        <path d="M70 306V324C70 343.9 86.1 360 106 360H406C425.9 360 442 343.9 442 324V306H70Z" fill="currentColor" />
                    </svg>
                </div>
            </div>
            <h2 class="text-5xl font-black text-white tracking-tight mb-6 leading-tigher">Manage Your Restaurant.<br>Like a Pro.</h2>
            <p class="text-xl text-slate-300 font-medium leading-relaxed max-w-md mx-auto">
                Satu aplikasi untuk mengatur pesanan, stok, dan laporan keuangan restoran Anda dengan efisien.
            </p>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white">
        <div class="w-full max-w-md space-y-8">
            
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center gap-2 mb-4 lg:mb-8">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                         <svg class="w-5 h-5" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M256 130C265.9 130 274 121.9 274 112C274 102.1 265.9 94 256 94C246.1 94 238 102.1 238 112C238 121.9 246.1 130 256 130Z" fill="currentColor" />
                            <path d="M256 148C176.5 148 108.8 198.7 82 270H430C403.2 198.7 335.5 148 256 148Z" fill="currentColor" />
                            <path d="M70 306V324C70 343.9 86.1 360 106 360H406C425.9 360 442 343.9 442 324V306H70Z" fill="currentColor" />
                        </svg>
                    </div>
                    <span class="text-2xl font-black text-slate-900 tracking-tighter">LaraCarte.</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Selamat Datang! 👋</h1>
                <p class="text-slate-500 mt-2">Silakan masuk untuk akses dashboard.</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit="login" class="space-y-6">
                
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-900" for="email">Email Address</label>
                    <input wire:model="form.email" id="email" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all font-medium text-slate-900 placeholder:text-slate-400"
                        type="email" name="email" required autofocus placeholder="admin@resto.com" />
                    @error('form.email') <p class="text-red-500 text-sm font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-sm font-semibold text-slate-900" for="password">Password</label>
                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors"
                                href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    <input wire:model="form.password" id="password" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 transition-all font-medium text-slate-900"
                        type="password" name="password" required placeholder="••••••••" />
                    @error('form.password') <p class="text-red-500 text-sm font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center">
                    <label for="remember" class="flex items-center cursor-pointer select-none group">
                        <input wire:model="form.remember" id="remember" type="checkbox" 
                            class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer w-5 h-5">
                        <span class="ml-2 text-sm text-slate-600 font-medium group-hover:text-slate-900 transition-colors">Ingat Saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full py-3.5 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transform transition-all duration-200 hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                    <span wire:loading.remove>Masuk Dashboard &rarr;</span>
                    <span wire:loading class="flex items-center gap-2">
                         <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </form>

            <div class="pt-8 text-center border-t border-slate-100">
                <p class="text-slate-500 text-sm mb-4">Belum punya akun restoran?</p>
                <a href="{{ route('register') }}" 
                    class="inline-block px-8 py-3 rounded-xl bg-slate-50 text-slate-900 border border-slate-200 text-sm font-bold hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition-all">
                    Daftar Sekarang
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