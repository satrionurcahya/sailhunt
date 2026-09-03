<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah admin sudah ada, jika belum buat
        $admin = Unit::where('username', 'admin')->first();

        if (!$admin) {
            Unit::create([
                'level' => 'Wira',
                'school_name' => 'ADMIN SAIL & HUNT',
                'address' => 'Kota Bandung',
                'city' => 'Kota Bandung',
                'postal_code' => '40123',
                'coach_name' => 'Admin',
                'trainer_name' => 'Admin',
                'commander_name' => 'Admin',
                'email' => 'admin@sailandhunt.com',
                'username' => 'admin',
                'password' => 'Admin123!', // password ini akan di-hash otomatis oleh model
                'status' => 'verified',
                'is_admin' => true,
            ]);
        } else {
            // Jika sudah ada, update status dan is_admin
            $admin->update([
                'status' => 'verified',
                'is_admin' => true,
            ]);
        }
    }
}