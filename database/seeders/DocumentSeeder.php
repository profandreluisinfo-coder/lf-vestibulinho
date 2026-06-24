<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            ['type' => 'RG', 'description' => 'Registro Geral'],
            ['type' => 'CIN', 'description' => 'Carteira de Identidade Nacional'],
            ['type' => 'RNM', 'description' => 'Registro Nacional de Migratório'],
        ];

        foreach ($documents as $document) {
            \App\Models\Document::updateOrCreate($document);
        }
    }
}
