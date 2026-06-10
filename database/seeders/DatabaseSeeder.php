<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin & Kasir Default
        User::create([
            'name' => 'Admin Laundry',
            'email' => 'admin@laundry.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Laundry',
            'email' => 'kasir@laundry.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);

        // 2. Buat Contoh Layanan awal
        Service::create([
            'name' => 'Cuci Kering Setrika (Kiloan)',
            'type' => 'kiloan',
            'price' => 7000,
            'estimated_hours' => 48 // 2 hari
        ]);

        Service::create([
            'name' => 'Cuci Premium Jas (Satuan)',
            'type' => 'satuan',
            'price' => 25000,
            'estimated_hours' => 72 // 3 hari
        ]);

        // 3. Buat Contoh Pelanggan awal
        Customer::create([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 10, Medan'
        ]);
    }
}
