<?php

namespace Database\Seeders\Auth;

use App\Models\Auth\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'Master Admin',
            'code' => 'MA',
            'description' => 'Ini adalah role Master Admin',
        ]);

        Role::create([
            'name' => 'Medical Staff',
            'code' => 'MS',
            'description' => 'Ini adalah role Medical Staff',
        ]);
    }
}