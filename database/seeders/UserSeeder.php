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
        User::updateOrCreate(
            ['email' => 'adm@lf.com'],
            [
                'name' => 'André Luís Alves',
                'cpf' => '12345678901',
                'role' => 'admin',
                'password' => Hash::make('123'),
                'email_verified_at' => now()
            ]
        );

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

        // Cria 10 usuários fake
        // User::factory(10)->create()->each(function ($user) {
        //     // Cria detalhes do usuário
        //     UserDetail::factory()->create([
        //         'user_id' => $user->id
        //     ]);

        //     // Cria 1 inscrição para o usuário
        //     Inscription::factory()->create([
        //         'user_id' => $user->id
        //     ]);
        // });
    }
}