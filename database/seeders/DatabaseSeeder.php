<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            LocalSeeder::class,
            CourseSeeder::class,
            UserSeeder::class,
            PostSeeder::class,
            // SettingsSeeder::class
        ]);
    }
}
