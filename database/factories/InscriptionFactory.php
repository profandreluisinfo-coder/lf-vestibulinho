<?php

namespace Database\Factories;

use App\Models\Inscription;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class InscriptionFactory extends Factory
{
    protected $model = Inscription::class;

    public function definition(): array
    {
        // return [
        //     'user_id'   => User::factory(),
        //     'course_id' => Course::inRandomOrder()->first()->id ?? Course::factory(),
        // ];

        return [
            'user_id' => User::factory(),
            'course_id' => function () {
                return Course::inRandomOrder()->first()->id;
            },
        ];
    }
}
