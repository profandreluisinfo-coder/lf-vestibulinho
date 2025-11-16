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
                'vacancies' => 0
            ],
            [
                'name' => 'CONTABILIDADE',
                'description' => 'TÉCNICO EM CONTABILIDADE - NOTURNO - 4 ANOS',
                'vacancies' => 0
            ],
            [
                'name' => 'INFORMÁTICA',
                'description' => 'TÉCNICO EM INFORMÁTICA (ÊNFASE EM PROGRAMAÇÃO PARA WEB) - NOTURNO - 4 ANOS',
                'vacancies' => 0
            ],
            [
                'name' => 'SEGURANÇA DO TRABALHO',
                'description' => 'TÉCNICO EM SEGURANÇA DO TRABALHO - NOTURNO - 4 ANOS',
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