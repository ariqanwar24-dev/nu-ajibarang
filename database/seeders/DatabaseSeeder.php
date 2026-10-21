<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Akun Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@nuajibarang.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('SuperAdmin123!'),
                'role' => User::ROLE_SUPER_ADMIN,
                'status' => User::STATUS_ACTIVE,
                'is_admin' => true,
            ]
        );

        // Akun Admin Biasa
        User::updateOrCreate(
            ['email' => 'admin@nuajibarang.id'],
            [
                'name' => 'Admin Biasa',
                'password' => Hash::make('AdminBiasa123!'),
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_ACTIVE,
                'is_admin' => true,
            ]
        );

        // Seeder data desa dan pengurus tetap dijalankan
        $this->call([
            DesaSeeder::class,
            PengurusSeeder::class,
        ]);
    }
}
