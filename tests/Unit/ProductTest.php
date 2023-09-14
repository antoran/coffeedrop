<?php

use App\Models\Product;

describe('cashback', function () {
    $product = new Product([
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
        ],
    ]);

    it('calculates chasback', fn (int $qty, float $expected) => test()
        ->expect($product->calculateCashback($qty))
        ->toBe($expected)
        ->toBeFloat())
        ->with([
            [-500, 0],
            [-1, 0],
            [0, 0],
            [1, 0.02],
            [2, 0.04],
            [50, 1],
            [51, 1.03],
            [52, 1.06],
            [500, 14.5],
            [501, 14.55]
        ]);
})->group('cashback');
