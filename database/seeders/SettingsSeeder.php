<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insert([
            'calendar' => false,
            'notice'   => false,
            'location' => false,
            'result'   => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}