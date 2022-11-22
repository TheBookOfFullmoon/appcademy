<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = fake()->randomElement(['Male', 'Female']);

        return [
            'name' => fake()->name(),
            'birthday' => fake()->date(),
            'birth_place' => fake()->country(),
            'address' => fake()->address(),
            'gender' => $gender,
            'phone' => fake()->phoneNumber(),
        ];
    }
}
