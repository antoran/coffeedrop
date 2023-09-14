<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->useHaversineMacro();
    }

    protected function useHaversineMacro(): void
    {
        QueryBuilder::macro('haversine', function (string|float $lat, string|float $lng, string $latCol = 'latitude', string $lngCol = 'longitude') {
            /** @var QueryBuilder $this  */
            return $this->selectRaw(
                "*, ( 6371 * acos( cos( radians(?) ) * cos( radians( {$latCol} ) ) * cos( radians( {$lngCol} ) - radians(?) ) + sin( radians(?) ) * sin( radians( {$latCol} ) ) ) ) AS distance",
                [$lat, $lng, $lat]
            );
        });
    }
}