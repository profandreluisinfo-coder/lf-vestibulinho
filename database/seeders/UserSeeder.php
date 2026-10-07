<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Inscription;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin fixo 1
        // User::updateOrCreate(
        //     ['email' => 'adm@lf.com'],
        //     [
        //         'name' => 'André Luís Alves',
        //         'cpf' => '12345678901',
        //         'role' => 'admin',
        //         'password' => Hash::make('123'),
        //         'email_verified_at' => now()
        //     ]
        // );

        // Admin fixo 2
        // User::updateOrCreate(
        //     ['email' => 'beatriz.castagna8@educacaosumare.com.br'],
        //     [
        //         'name' => 'BEATRIZ ZANETTI RAMOS CASTAGNA',
        //         'cpf' => '12345678902',
        //         'role' => 'admin',
        //         'password' => Hash::make('123'),
        //         'email_verified_at' => now()
        //     ]
        // );

        // Admin fixo 3
        // User::updateOrCreate(
        //     ['email' => 'jusdecampos.jdc@gmail.com'],
        //     [
        //         'name' => 'JUSSARA DE CAMPOS',
        //         'cpf' => '12345678903',
        //         'role' => 'admin',
        //         'password' => Hash::make('123'),
        //         'email_verified_at' => now()
        //     ]
        // );

        User::updateOrCreate(
            ['email' => 'donizete@lf.com'],
            [
                'name' => 'Donizete',
                'cpf' => '12345678904',
                'role' => 'admin',
                'password' => Hash::make('123'),
                'email_verified_at' => now()
            ]
        );
    }
}