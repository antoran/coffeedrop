<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word,
            'prices' => [
                '0' => fake()->randomFloat(2, 0, 1),
                '51' => fake()->randomFloat(2, 0, 1),
                '501' => fake()->randomFloat(2, 0, 1),
            ]
        ];
    }
}
