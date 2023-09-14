<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Product::factory()->create([
            'name' => 'Ristretto',
            'prices' => [
                [
                    'min' => 0,
                    'max' => 50,
                    'rate' => 0.02,
                ],
                [
                    'min' => 50,
                    'max' => 500,
                    'rate' => 0.03,
                ],
                [
                    'min' => 500,
                    'max' => null,
                    'rate' => 0.05,
                ],
            ]
        ]);

        \App\Models\Product::factory()->create([
            'name' => 'Espresso',
            'prices' => [
                [
                    'min' => 0,
                    'max' => 50,
                    'rate' => 0.04,
                ],
                [
                    'min' => 50,
                    'max' => 500,
                    'rate' => 0.06,
                ],
                [
                    'min' => 500,
                    'max' => null,
                    'rate' => 0.1,
                ],
            ]
        ]);

        \App\Models\Product::factory()->create([
            'name' => 'Lungo',
            'prices' => [
                [
                    'min' => 0,
                    'max' => 50,
                    'rate' => 0.06,
                ],
                [
                    'min' => 50,
                    'max' => 500,
                    'rate' => 0.09,
                ],
                [
                    'min' => 500,
                    'max' => null,
                    'rate' => 0.15,
                ],
            ]
        ]);
    }
}
