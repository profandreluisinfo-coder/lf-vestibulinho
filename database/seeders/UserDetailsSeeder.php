<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserDetail;
use App\Models\User;

class UserDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            UserDetail::create([
                'user_id'           => $user->id,
                'nationality'       => fake()->randomElement(['1','2']),
                'doc_type'          => fake()->randomElement(['1','2']),
                'doc_number'        => fake()->unique()->numerify('###########'),
                'phone'             => fake()->numerify('###########'),

                'new_number'        => fake()->optional()->regexify('[0-9]{32}'),
                'fls'               => fake()->optional()->numerify('####'),
                'book'              => fake()->optional()->regexify('[A-Z0-9]{1,10}'),
                'old_number'        => fake()->optional()->numerify('######'),
                'municipality'      => fake()->city(),

                'zip'               => fake()->numerify('########'),
                'street'            => fake()->streetName(),
                'number'            => fake()->optional()->buildingNumber(),
                'complement'        => fake()->optional()->word(),
                'burgh'             => fake()->word(),
                'city'              => fake()->city(),
                'state'             => fake()->state(),

                'school_name'       => fake()->company(),
                'school_city'       => fake()->city(),
                'school_state'      => fake()->state(),
                'school_year'       => fake()->year(),
                'school_ra'         => fake()->unique()->bothify('RA########'),

                'mother'            => fake()->name('female'),
                'mother_phone'      => fake()->optional()->numerify('###########'),
                'father'            => fake()->optional()->name('male'),
                'father_phone'      => fake()->optional()->numerify('###########'),
                'responsible'       => fake()->optional()->name(),
                'degree'            => fake()->optional()->randomElement(['1','2','3']),
                'kinship'           => fake()->optional()->word(),
                'responsible_phone' => fake()->optional()->numerify('###########'),
                'parents_email'     => fake()->safeEmail(),

                'health'            => fake()->optional()->word(),
                'accessibility'     => fake()->optional()->word(),
                'nis'               => fake()->optional()->numerify('###########'),
            ]);
        }
    }
}