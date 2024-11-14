<?php

namespace Database\Factories;

use App\Models\Schools;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SchoolsFactory extends Factory
{

    protected $model = Schools::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'school_code' => fake()->word(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'dist' => fake()->numberBetween($min = 1, $max = 500),
            'state' => fake()->numberBetween($min = 1, $max = 30),
            'pin' => fake()->randomNumber(6, true),
        ];
    }
}
