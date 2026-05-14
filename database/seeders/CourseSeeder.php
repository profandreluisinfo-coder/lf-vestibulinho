<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Administração',
                'delay' => 1,
                'card' => 'cc-admin',
                'icone' => 'briefcase-fill',
                'description' => 'TÉCNICO EM ADMINISTRAÇÃO - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Gestão empresarial, finanças, recursos humanos e organização de processos corporativos.',
                'vacancies' => 0
            ],
            [
                'name' => 'Contabilidade',
                'delay' => 2,
                'card' => 'cc-cont',
                'icone' => 'calculator-fill',
                'description' => 'TÉCNICO EM CONTABILIDADE - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Escrituração contábil, tributos, balanços patrimoniais e análise financeira.',
                'vacancies' => 0
            ],
            [
                'name' => 'Informática',
                'delay' => 3,
                'card' => 'cc-info',
                'icone' => 'laptop-fill',
                'description' => 'TÉCNICO EM INFORMÁTICA (ÊNFASE EM PROGRAMAÇÃO PARA WEB) - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Programação, redes, web design e desenvolvimento de sistemas computacionais.',
                'vacancies' => 0
            ],
            [
                'name' => 'Segurança do Trabalho',
                'delay' => 4,
                'card' => 'cc-seg',
                'icone' => 'shield-fill-check',
                'description' => 'TÉCNICO EM SEGURANÇA DO TRABALHO - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Prevenção de acidentes, normas regulamentadoras (NRs) e saúde ocupacional.',
                'vacancies' => 0
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['name' => $course['name']], // critério de unicidade
                $course
            );
        }
    }
}