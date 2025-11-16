<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();

        // Definindo o nome completo
        $fullName = "{$firstName} {$lastName}";

        // Social name: pode ser o primeiro nome ou o nome reduzido
        // $socialName = $this->faker->randomElement([$firstName, $this->getShortenedName($fullName)]);

        return [
            // 'cpf' => $this->generateCpf(),
            'cpf' => $this->faker->unique()->numerify('###########'),

            'name' => $fullName,
            // 'social_name' => $socialName,

            'birth' => $this->faker->date('Y-m-d'),
            'gender' => $this->faker->randomElement(['1', '2', '3', '4']),

            // 'pne' => $this->faker->randomElement(['0', '1']),
            'pne' => 0,

            'email' => $this->faker->unique()->safeEmail(),

            'email_verified_at' => now(),

            'role' => 'user',

            'last_login_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),

            'password' => Hash::make('password'), // ou bcrypt('senha') se preferir
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Gera um nome reduzido ou apelido.
     * Ex: José Antônio vira Antônio.
     */
    // private function getShortenedName(string $fullName): string
    // {
    //     $nameParts = explode(" ", $fullName);

    //     // Se o nome for composto, pegar o primeiro nome
    //     if (count($nameParts) > 1) {
    //         return $nameParts[1]; // Pega o segundo nome, tipo "Antônio" de "José Antônio"
    //     }

    //     return $fullName; // Se o nome não for composto, usa o nome todo
    // }

    private function generateCpf(): string
    {
        return $this->faker->numerify('###########');
    }
}
