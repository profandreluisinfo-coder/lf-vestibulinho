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
            ['category' => 'Cursos'],
            ['category' => 'Geral'],
            ['category' => 'Inscrição'],
            ['category' => 'Matrícula'],
            ['category' => 'Prova'],
            ['category' => 'Resultado'],
        ];
        foreach ($categories as $category) {
            Category::updateOrCreate($category);
        }
    }
}