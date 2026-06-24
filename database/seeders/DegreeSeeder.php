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
            ['description' => 'Padrasto'],
            ['description' => 'Madrasta'],
            ['description' => 'Avô(ó)'],
            ['description' => 'Tio(a)'],
            ['description' => 'Irmão(ã)'],
            ['description' => 'Primo(a)'],
            ['description' => 'Outro'],
        ];

        foreach ($degrees as $degree) {
            Degree::updateOrCreate($degree);
        }
    }
}
