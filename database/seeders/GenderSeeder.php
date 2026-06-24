<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            ['description' => 'Masculino'],
            ['description' => 'Feminino'],
            ['description' => 'Outro'],
            ['description' => 'Prefiro nao informar'],
        ];

        foreach ($genders as $gender) {
            \App\Models\Gender::updateOrCreate($gender);
        }
    }
}
