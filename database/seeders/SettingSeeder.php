<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'store_name',
                'value' => 'LaraCarte Resto',
                'description' => 'Nama Toko/Restoran'
            ],
            [
                'key' => 'store_address',
                'value' => 'Jl. Digital No. 1, Internet',
                'description' => 'Alamat Toko di Struk'
            ],
            [
                'key' => 'tax_rate',
                'value' => '11',
                'description' => 'Persentase Pajak (PPN)'
            ],
            [
                'key' => 'service_charge',
                'value' => '5',
                'description' => 'Persentase Service Charge'
            ],
            [
                'key' => 'midtrans_server_key',
                'value' => '',
                'description' => 'Midtrans Server Key'
            ],
            [
                'key' => 'midtrans_client_key',
                'value' => '',
                'description' => 'Midtrans Client Key'
            ],
            [
                'key' => 'midtrans_is_production',
                'value' => '0',
                'description' => '0 untuk Sandbox, 1 untuk Production'
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
