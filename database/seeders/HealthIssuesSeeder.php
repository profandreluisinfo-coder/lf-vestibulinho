<?php

namespace Database\Seeders;

use App\Models\HealthIssue;
use Illuminate\Database\Seeder;

class HealthIssuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $healthIssues = [
            ['description' => 'Hipertensão Arterial'],
            ['description' => 'Diabetes Mellitus - Tipo 1'],
            ['description' => 'Diabetes Mellitus - Tipo 2'],
            ['description' => 'Asma'],
            ['description' => 'Doença Pulmonar Obstrutiva Crônica (DPOC)'],
            ['description' => 'Depressão'],
            ['description' => 'Ansiedade'],
            ['description' => 'Doença Cardíaca (Cardiopatia)'],
            ['description' => 'Artrite Reumatoide'],
            ['description' => 'Alergias'],
            ['description' => 'Enxaqueca Crônica'],
            ['description' => 'Câncer'],
            ['description' => 'Insuficiência Renal Crônica']
        ];

        foreach ($healthIssues as $healthIssue) {
            HealthIssue::updateOrCreate($healthIssue);
        }
    }
}