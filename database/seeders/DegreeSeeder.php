<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $degrees = [
            ['id' => 1, 'description' => 'Padrasto'],
            ['id' => 2, 'description' => 'Madrasta'],
            ['id' => 3, 'description' => 'Avô(ó)'],
            ['id' => 4, 'description' => 'Tio(a)'],
            ['id' => 5, 'description' => 'Irmão(ã)'],
            ['id' => 6, 'description' => 'Primo(a)'],
            ['id' => 7, 'description' => 'Cunhado(a)'],
            ['id' => 8, 'description' => 'Outro'],
        ];

        foreach ($degrees as $degree) {
            Degree::updateOrCreate($degree);
        }
    }
}
