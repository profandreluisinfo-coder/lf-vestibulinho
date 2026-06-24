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
            ['description' => 'Física - Amputado'],
            ['description' => 'Auditiva - Moderada'],
            ['description' => 'Auditiva - Severa'],
            ['description' => 'Auditiva - Profunda'],
            ['description' => 'Intelectual - Moderada'],
            ['description' => 'Intelectual - Severo'],
            ['description' => 'Transtorno Específico da Aprendizagem'],
            ['description' => 'Transtornos do Espectro Autista - Nível 1'],
            ['description' => 'Transtornos do Espectro Autista - Nível 2'],
            ['description' => 'Transtornos do Espectro Autista - Nível 3'],
            ['description' => 'Síndrome de Down'],
            ['description' => 'Múltiplas'],
            ['description' => 'Física - Paralisia Cerebral'],
            ['description' => 'Física - Hemiplegia'],
            ['description' => 'Física - Hemiparesia'],
            ['description' => 'Física - Monoplegia'],
            ['description' => 'Física - Monoparesia'],
            ['description' => 'Física - Paraplegia'],
            ['description' => 'Física - Paraparesia'],
            ['description' => 'Física - Tetraplegia'],
            ['description' => 'Física - Tetraparesia'],
            ['description' => 'Física - Diplegia'],
            ['description' => 'Física - Diparesia'],
            ['description' => 'Física - Hemiplegia e Hemiparesia'],
            ['description' => 'Física - Monoplegia e Monoparesia'],
            ['description' => 'Física - Paraplegia e Paraparesia'],
            ['description' => 'Física - Tetraplegia e Tetraparesia'],
            ['description' => 'Física - Hemiplegia e Hemiparesia'],
            ['description' => 'Física - Monoplegia e Monoparesia'],
            ['description' => 'Física - Paraplegia e Paraparesia'],
            ['description' => 'Física - Tetraplegia e Tetraparesia'],
        ];

        foreach ($disabilities as $disability) {
            \App\Models\Disability::updateOrCreate($disability);
        }
    }
}
