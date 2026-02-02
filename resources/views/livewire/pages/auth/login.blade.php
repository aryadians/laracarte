<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false));
};

?>

<div
    class="fixed inset-0 z-50 min-h-screen bg-slate-50 flex items-center justify-center overflow-hidden font-sans text-slate-600">

    <div id="vanta-bg" class="absolute inset-0 z-0"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js" defer></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            VANTA.WAVES({
                el: "#vanta-bg",
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
        })
    </script>

    <div class="absolute inset-0 w-full h-full pointer-events-none bg-slate-50/10">
    </div>

    <div class="relative w-full max-w-[420px] px-6">

        <div class="text-center mb-8 animate-fade-in-down">
            <div
                class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-indigo-600 to-purple-600 shadow-xl shadow-indigo-500/30 mb-5 transform transition hover:scale-105 duration-300">
                <svg width="40" height="40" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M256 130C265.9 130 274 121.9 274 112C274 102.1 265.9 94 256 94C246.1 94 238 102.1 238 112C238 121.9 246.1 130 256 130Z"
                        fill="white" />
                    <path d="M256 148C176.5 148 108.8 198.7 82 270H430C403.2 198.7 335.5 148 256 148Z" fill="white" />
                    <path d="M70 306V324C70 343.9 86.1 360 106 360H406C425.9 360 442 343.9 442 324V306H70Z"
                        fill="white" />
                </svg>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Lara<span
                    class="text-indigo-600">Carte.</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1 tracking-wide">Restaurant Management System</p>
        </div>

        <div
            class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 p-8 sm:p-10 relative overflow-hidden">

            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form wire:submit="login" class="flex flex-col gap-6">

                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1" for="email">
                        Email Address
                    </label>
                    <div class="relative group">
                        <div
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors pointer-events-none z-10">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input wire:model="form.email" id="email"
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 rounded-2xl text-slate-700 font-bold placeholder-slate-400 text-sm transition-all shadow-sm outline-none relative z-0"
                            type="email" name="email" required autofocus placeholder="admin@laracarte.com" />
                    </div>
                    @error('form.email') <p class="text-red-500 text-xs font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1"
                        for="password">
                        Password
                    </label>
                    <div class="relative group">
                        <div
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors pointer-events-none z-10">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input wire:model="form.password" id="password"
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 rounded-2xl text-slate-700 font-bold placeholder-slate-400 text-sm transition-all shadow-sm outline-none relative z-0"
                            type="password" name="password" required placeholder="••••••••" />
                    </div>
                    @error('form.password') <p class="text-red-500 text-xs font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember" class="flex items-center cursor-pointer group select-none">
                        <div class="relative">
                            <input wire:model="form.remember" id="remember" type="checkbox" class="peer sr-only">
                            <div
                                class="w-5 h-5 border-2 border-slate-300 rounded-lg peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all bg-white">
                            </div>
                            <svg class="absolute top-0.5 left-0.5 w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span
                            class="ml-2 text-xs font-bold text-slate-500 group-hover:text-indigo-600 transition-colors">Ingat
                            Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-xs font-bold text-indigo-500 hover:text-indigo-700 hover:underline transition-colors"
                            href="{{ route('password.request') }}" wire:navigate>
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full py-4 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/40 transform transition-all duration-300 hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 group mt-2">
                    <span wire:loading.remove>MASUK DASHBOARD</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                    <svg wire:loading.remove class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>

            <div class="mt-8 text-center border-t border-slate-100 pt-6">
                <p class="text-slate-500 text-xs font-medium mb-3">Ingin membuka restoran baru?</p>
                <a href="{{ route('register') }}" 
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-50 text-indigo-600 text-xs font-bold hover:bg-indigo-100 hover:text-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    DAFTAR SEBAGAI MERCHANT
                </a>
            </div>
        </div>

        <p class="text-center text-slate-400 text-[10px] font-bold mt-8 tracking-widest uppercase opacity-60">
            © 2026 LaraCarte System.
        </p>
    </div>
</div>