<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nationalities = [
            ['description' => 'Brasileiro'],
            ['description' => 'Estrangeiro'],
        ];

        foreach ($nationalities as $nationality) {
            \App\Models\Nationality::updateOrCreate($nationality);
        }
    }
}