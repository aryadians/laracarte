<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-super-admin {name?} {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Super Admin user for the platform';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?: $this->ask('Nama Super Admin?');
        $email = $this->argument('email') ?: $this->ask('Email Super Admin?');
        $password = $this->argument('password') ?: $this->secret('Password Super Admin?');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            $this->info('Gagal membuat Super Admin:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => UserRole::SUPER_ADMIN,
            'tenant_id' => null, // Super Admin tidak terikat tenant
        ]);

        $this->info("Super Admin '{$user->name}' berhasil dibuat!");
        $this->info("Login menggunakan email: {$user->email}");
        
        return 0;
    }
}
