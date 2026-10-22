<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Administração',
                'delay' => 1,
                'card' => 'cc-admin',
                'icone' => 'briefcase-fill',
                'description' => 'TÉCNICO EM ADMINISTRAÇÃO INTEGRADO AO ENSINO MÉDIO - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Gestão empresarial, finanças, recursos humanos e organização de processos corporativos.',
            ],
            [
                'name' => 'Contabilidade',
                'delay' => 2,
                'card' => 'cc-cont',
                'icone' => 'calculator-fill',
                'description' => 'TÉCNICO EM CONTABILIDADE INTEGRADO AO ENSINO MÉDIO - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Escrituração contábil, tributos, balanços patrimoniais e análise financeira.',
            ],
            [
                'name' => 'Informática',
                'delay' => 3,
                'card' => 'cc-info',
                'icone' => 'laptop-fill',
                'description' => 'TÉCNICO EM INFORMÁTICA (ÊNFASE EM PROGRAMAÇÃO PARA WEB) INTEGRADO AO ENSINO MÉDIO - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Programação, redes, web design e desenvolvimento de sistemas computacionais.',
            ],
            [
                'name' => 'Segurança do Trabalho',
                'delay' => 4,
                'card' => 'cc-seg',
                'icone' => 'shield-fill-check',
                'description' => 'TÉCNICO EM SEGURANÇA DO TRABALHO INTEGRADO AO ENSINO MÉDIO - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Prevenção de acidentes, normas regulamentadoras (NRs) e saúde ocupacional.',
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['name' => $course['name']],
                $course
            );
        }
    }
}