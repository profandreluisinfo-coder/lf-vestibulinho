<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'donizete@lf.com'],
            [
                'name' => 'Donizete',
                'cpf' => '12345678904',
                'role' => 'admin',
                'password' => Hash::make('VestLF@2027'),
                'email_verified_at' => now()
            ]
        );
    }
}