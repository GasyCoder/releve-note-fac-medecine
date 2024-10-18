<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create(attributes: [
            'name' => 'Scolarité',
            'email' => 'scol@facmed.com',
            'password' => Hash::make('scol@facmed.com'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
