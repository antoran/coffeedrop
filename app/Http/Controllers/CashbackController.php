<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\CashbackRequest;
use App\Http\Resources\CashbackResource;

class CashbackController extends Controller
{
    public function __invoke(CashbackRequest $request)
    {
        $coffee = $request->only(['Ristretto', 'Espresso', 'Lungo']);

        $coffee = collect($coffee)->map(function ($quantity, $product) {
            return Product::where('name', $product)->first()->calculateCashback($quantity);
        });

        return new CashbackResource($coffee);
    }
}
