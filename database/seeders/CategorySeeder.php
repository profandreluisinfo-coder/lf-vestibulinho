<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Clube de Xadrez'],
            ['name' => 'Congresso'],
            ['name' => 'Convocação'],
            ['name' => 'Cursos'],
            ['name' => 'Encontro'],
            ['name' => 'Estágio'],
            ['name' => 'Feira Científica'],
            ['name' => 'Geral'],
            ['name' => 'Inscrição'],
            ['name' => 'Matrícula'],
            ['name' => 'Oficina'],
            ['name' => 'Outros'],
            ['name' => 'Palestra'],
            ['name' => 'Prova'],
            ['name' => 'Resultado'],
            ['name' => 'Reunião de pais'],
            ['name' => 'Seminário'],
            ['name' => 'Simpósio'],
            ['name' => 'Vestibular'],
            ['name' => 'Vestibulinho'],
            ['name' => 'Visita Técnica'],
            ['name' => 'Workshop'],
        ];
        foreach ($categories as $category) {
            Category::updateOrCreate($category);
        }
    }
}
