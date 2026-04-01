<?php

namespace Database\Seeders\Auth;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Master Admin
        $admin = User::create([
            'email' => 'admin@nexavira.com',
            'password' => Hash::make('password'),
            'is_active' => 1,
            'version' => 0,
        ]);

        $admin->userInformation()->create([
            'user_id' => $admin->id,
            'full_name' => 'Master Admin',
            'phone_number' => '08123456789',
        ]);

        $admin->userRole()->create([
            'user_id' => $admin->id,
            'role_id' => 1,
        ]);

        // Medical Staff
        $medicalStaff = User::create([
            'email' => 'dimas@nexavira.com',
            'password' => Hash::make('password'),
            'is_active' => 1,
            'version' => 0,
        ]);

        $medicalStaff->userInformation()->create([
            'user_id' => $medicalStaff->id,
            'full_name' => 'Dimas',
            'phone_number' => '08123456789',
        ]);

        $medicalStaff->userRole()->create([
            'user_id' => $medicalStaff->id,
            'role_id' => 2,
        ]);


        // $this->command->info('Akun Master Admin berhasil dibuat!');
    }
}