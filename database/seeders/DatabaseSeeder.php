<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SchoolSeeder::class,
            NationalitySeeder::class,
            DocumentSeeder::class,
            GenderSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            LocalSeeder::class,
            CourseSeeder::class,
            PostSeeder::class,
            SettingsSeeder::class
        ]);
    }
}