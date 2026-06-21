<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genders')->insert([
            ['description' => 'Masculino'],
            ['description' => 'Feminino'],
            ['description' => 'Outro'],
            ['description' => 'Prefiro nao informar'],
        ]);
    }
}
