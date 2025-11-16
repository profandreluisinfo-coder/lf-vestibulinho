<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('exam_locations')->insert([
            [
                'name' => 'EM DR LEANDRO FRANCESCHINI',
                'address' => 'R. GERALDO DE SOUZA, 221 - JARDIM SAO CARLOS, SUMARÉ - SP, 13170-232',
                'rooms_available' => 30
            ],
            [
                'name' => 'EE DOM JAYME DE BARROS CÂMARA',
                'address' => 'AVENIDA JOSE MANCINI, 501 JARDIM CARLOS BASSO, SUMARÉ - SP, 13170-040	',
                'rooms_available' => 9
            ]
        ]);
    }
}