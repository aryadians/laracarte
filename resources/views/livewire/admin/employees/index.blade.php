<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Enums\UserRole;

new #[Layout('components.admin-layout')] class extends Component
{
    public function with(): array
    {
        return [
            'employees' => User::where('tenant_id', auth()->user()->tenant_id)
                ->where('id', '!=', auth()->id()) // Don't show self
                ->latest()
                ->get(),
        ];
    }
    
    public function delete($id)
    {
        $user = User::findOrFail($id);
        
        // Security check
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
        
        $user->delete();
        
        $this->dispatch('employee-deleted'); 
    }
}; ?>

<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Karyawan 👥</h1>
            <p class="text-slate-500 mt-1 text-lg">Kelola staf restoran Anda, atur peran dan akses mereka.</p>
        </div>
        
        <a href="{{ route('employees.create') }}" class="group relative px-6 py-3 font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all duration-300">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Karyawan
            </span>
        </a>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl shadow-sm flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-bold text-green-800">Berhasil!</p>
                    <p class="text-sm text-green-700">{{ session('message') }}</p>
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th scope="col" class="px-6 py-4">Nama Lengkap</th>
                        <th scope="col" class="px-6 py-4">Peran (Role)</th>
                        <th scope="col" class="px-6 py-4">Bergabung</th>
                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($employees as $employee)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg ring-2 ring-white shadow-sm">
                                        {{ substr($employee->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-slate-900">{{ $employee->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $employee->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                {{ $employee->hasRole(UserRole::CASHIER) ? 'bg-green-100 text-green-700' : '' }}
                                {{ $employee->hasRole(UserRole::KITCHEN) ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $employee->hasRole(UserRole::WAITER) ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ $employee->role->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-medium">
                            {{ $employee->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button wire:click="delete({{ $employee->id }})" 
                                wire:confirm="Apakah Anda yakin ingin menghapus karyawan ini? Akses mereka akan dicabut permanen."
                                class="text-red-500 hover:text-red-700 font-bold text-xs bg-red-50 px-4 py-2 rounded-lg hover:bg-red-100 transition duration-200">
                                Hapus Akses
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span class="font-medium">Belum ada karyawan.</span>
                                <span class="text-xs mt-1">Tambahkan karyawan baru untuk membantu operasional restoran Anda.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
