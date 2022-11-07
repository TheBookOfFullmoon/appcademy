<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class LecturerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'birthday' => fake()->date(),
            'birth_place' => fake()->country(),
            'address' => fake()->address(),
            'gender' => 'male',
            'phone' => fake()->phoneNumber(),
        ];
    }
}