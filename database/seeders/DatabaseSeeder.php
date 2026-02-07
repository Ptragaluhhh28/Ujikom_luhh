<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin@motor.com',
            'no_tlpn' => '0811111111',
            'role' => 'admin',
            'password' => \Hash::make('password123'),
        ]);

        // 2. Create Owner
        $owner = User::create([
            'nama' => 'Budi Pemilik',
            'email' => 'owner@motor.com',
            'no_tlpn' => '0822222222',
            'role' => 'owner',
            'password' => \Hash::make('password123'),
        ]);

        // 3. Create Renter
        User::create([
            'nama' => 'Andi Penyewa',
            'email' => 'renter@motor.com',
            'no_tlpn' => '0833333333',
            'role' => 'renter',
            'password' => \Hash::make('password123'),
        ]);

        // 4. Create Sample Motor for the owner
        $motor = \App\Models\Motor::create([
            'pemilik_id' => $owner->id,
            'merk' => 'Honda Vario 150',
            'tipe_cc' => 150,
            'no_plat' => 'Z 4567 ABC',
            'status' => 'tersedia',
        ]);

        \App\Models\TarifRental::create([
            'motor_id' => $motor->id,
            'tarif_harian' => 75000,
            'tarif_mingguan' => 450000,
            'tarif_bulanan' => 1200000,
        ]);
        
        $motor2 = \App\Models\Motor::create([
            'pemilik_id' => $owner->id,
            'merk' => 'Yamaha NMAX',
            'tipe_cc' => 150,
            'no_plat' => 'Z 1234 XYZ',
            'status' => 'tersedia',
        ]);

        \App\Models\TarifRental::create([
            'motor_id' => $motor2->id,
            'tarif_harian' => 100000,
            'tarif_mingguan' => 600000,
            'tarif_bulanan' => 1800000,
        ]);
    }
}
