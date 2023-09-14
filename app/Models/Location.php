<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'postcode',
        'latitude',
        'longitude',
        'times',
    ];

    protected $casts = [
        'times' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
    ];
}
