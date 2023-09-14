<?php

use Database\Seeders\ProductSeeder;

use function Pest\Laravel\{seed};

beforeEach(function () {
    seed(ProductSeeder::class);
});

describe('cashback', function () {
    it('can post cashback', fn () => test()
        ->postJson(route('api.v1.cashback', [
            'Ristretto' => 1,
            'Espresso' => 2,
            'Lungo' => 3,
        ]))->assertOk()
        ->assertJson([
            'data' => [
                'ristretto' => 0.02,
                'espresso' => 0.08,
                'lungo' => 0.18,
                'sum' => 0.28
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'ristretto',
                'espresso',
                'lungo',
                'sum',
            ]
        ]));

    it('cannot post cashback with invalid data', fn () => test()
        ->postJson(route('api.v1.cashback', [
            'Ristretto' => 'invalid',
            'Espresso' => 'invalid',
            'Lungo' => 'invalid',
        ]))->assertStatus(422)
        ->assertJsonValidationErrors([
            'Ristretto',
            'Espresso',
            'Lungo',
        ]));

    it('cannot post cashback with missing data', fn () => test()
        ->postJson(route('api.v1.cashback'))->assertStatus(422)
        ->assertJsonValidationErrors([
            'Ristretto',
            'Espresso',
            'Lungo',
        ]));

    it('can post cashback with extra data', fn () => test()
        ->postJson(route('api.v1.cashback', [
            'Ristretto' => 1,
            'Espresso' => 2,
            'Lungo' => 3,
            'Extra' => 4,
        ]))->assertOk()
        ->assertJson([
            'data' => [
                'ristretto' => 0.02,
                'espresso' => 0.08,
                'lungo' => 0.18,
                'sum' => 0.28
            ]
        ]));
})->group('cashback');
