<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // unique by email
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('12345678'), // set your password here
                // Add other fields if needed, e.g. role_id, is_admin, etc.
            ]
        );
    }
}
