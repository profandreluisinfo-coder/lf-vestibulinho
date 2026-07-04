<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SchoolSeeder::class,
            DegreeSeeder::class,
            HealthIssuesSeeder::class,
            ResourceSeeder::class,
            DisabilitySeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            LocalSeeder::class,
            CourseSeeder::class,
            PostSeeder::class,
            SettingsSeeder::class,
            ProcessSeeder::class
        ]);
    }
}
