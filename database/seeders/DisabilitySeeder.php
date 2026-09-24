<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DisabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disabilities = [
            ['description' => 'Visual - Baixa Visão'],
            ['description' => 'Visual - Cegueira'],
            ['description' => 'Visual - Visão monocular'],
            ['description' => 'Auditiva - Perda Auditiva'],
            ['description' => 'Auditiva - Surdez'],
            ['description' => 'Transtornos do Espectro Autista - Nível 1'],
            ['description' => 'Transtornos do Espectro Autista - Nível 2'],
            ['description' => 'Transtornos do Espectro Autista - Nível 3']
        ];

        foreach ($disabilities as $disability) {
            \App\Models\Disability::updateOrCreate($disability);
        }
    }
}
