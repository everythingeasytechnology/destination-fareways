<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@destinationfareways.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123456'),
                'role' => 'superadmin',
                'is_active' => true,
                'avatar' => null,
            ]
        );
    }
}
