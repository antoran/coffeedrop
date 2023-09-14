<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'prices',
    ];

    protected $casts = [
        'prices' => 'array',
    ];

    public function calculateCashback(int $quantity): float
    {
        $subtotal = 0.0;

        foreach ($this->prices as [
            'min' => $min,
            'max' => $max,
            'rate' => $rate,
        ]) {
            if ($max && ($max-$min) <= $quantity) {
                $subtotal += ($max - $min) * $rate;
            } elseif ($quantity >= $min) {
                $subtotal += ($quantity - $min) * $rate;
            }
        }

        return round($subtotal, 2);
    }
}
