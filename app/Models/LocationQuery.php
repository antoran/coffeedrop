<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'postcode',
        'latitude',
        'longitude',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    /**
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function scopeFindOrCreateByPostcode(Builder $query, string $postcode): ?LocationQuery
    {
        // format postcode to match API response
        $postcode = str($postcode)->upper()->replace(' ', '')->value();

        return $query->where('postcode', $postcode)->firstOr(function () use ($postcode) {
            $response = Http::get('https://api.postcodes.io/postcodes/' . $postcode)->throw()->json();

            return $this->create([
                'postcode' => $postcode,
                'latitude' => $response['result']['latitude'],
                'longitude' => $response['result']['longitude'],
                'response' => $response,
            ]);
        });
    }
}
