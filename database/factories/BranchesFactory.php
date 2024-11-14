<?php

namespace Database\Factories;

use App\Models\Schools;
use App\Models\Branches;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branches>
 */
class BranchesFactory extends Factory
{

    protected $model = Branches::class;

    public function definition()
    {
        return [
            'branch_name' => fake()->company(),
            'branch_code' => fake()->word(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'dist' => fake()->numberBetween($min = 1, $max = 500),
            'state' => fake()->numberBetween($min = 1, $max = 30),
            'pin' => fake()->randomNumber(6, true),
        ];
    }

    public function multiple($count = 1)
    {
        return array_map(function () {
            return $this->definition();
        }, range(1, $count));
    }
}
