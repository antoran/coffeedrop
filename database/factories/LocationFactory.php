<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'postcode' => fake('en_gb')->postcode,
            'latitude' => fake('en_gb')->latitude,
            'longitude' => fake('en_gb')->longitude,
            'times' => [
                'opening_times' => [
                    ...[strtolower(fake()->dayOfWeek) => fake()->time('H:i')]
                ],
                'closing_times' => [
                    ...[strtolower(fake()->dayOfWeek) => fake()->time('H:i')]
                ],
            ],
        ];
    }
}
