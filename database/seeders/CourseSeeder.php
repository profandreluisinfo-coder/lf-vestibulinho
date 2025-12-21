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
                'name' => 'ADMINISTRAÇÃO',
                'description' => 'TÉCNICO EM ADMINISTRAÇÃO - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Forme-se para atuar em empresas de diversos segmentos, desenvolvendo competências em gestão, planejamento e organização empresarial.',
                'vacancies' => 0
            ],
            [
                'name' => 'CONTABILIDADE',
                'description' => 'TÉCNICO EM CONTABILIDADE - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Capacite-se para trabalhar com controle financeiro, análise contábil e assessoria fiscal em empresas e escritórios contábeis.',
                'vacancies' => 0
            ],
            [
                'name' => 'INFORMÁTICA',
                'description' => 'TÉCNICO EM INFORMÁTICA (ÊNFASE EM PROGRAMAÇÃO PARA WEB) - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Desenvolva habilidades em programação, manutenção de computadores, redes e suporte técnico para o mercado de TI.',
                'vacancies' => 0
            ],
            [
                'name' => 'SEGURANÇA DO TRABALHO',
                'description' => 'TÉCNICO EM SEGURANÇA DO TRABALHO - NOTURNO - 4 ANOS',
                'duration' => 4,
                'info' => 'Torne-se especialista em prevenção de acidentes e promoção da saúde e segurança no ambiente de trabalho.',
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