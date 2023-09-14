<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\LocationQuery;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Http\Resources\LocationCollection;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Requests\NearestLocationRequest;

class LocationController extends Controller
{
    public function index(Request $request): LocationCollection
    {
        $validated = $request->validate([
            'postcode' => 'nullable|string|postcode',
        ]);

        $locations = Location::when($validated['postcode'] ?? null, function($query, $postcode) {
            $location = LocationQuery::findOrCreateByPostcode($postcode);

            return $query->haversine($location->latitude, $location->longitude)
                ->orderBy('distance', 'asc');
        })->get();

        return new LocationCollection($locations);
    }

    public function nearest(NearestLocationRequest $request): LocationResource
    {
        $location = Location::when($request->postcode, function($query, $postcode) {
            $location = LocationQuery::findOrCreateByPostcode($postcode);

            return $query->haversine($location->latitude, $location->longitude)
                ->orderBy('distance', 'asc');
        })->first();

        return new LocationResource($location);
    }

    public function store(StoreLocationRequest $request): LocationResource
    {
        $validated = $request->validated();

        $location = LocationQuery::findOrCreateByPostcode($validated['postcode']);

        $location = Location::create([
            'postcode' => $validated['postcode'],
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'times' => [
                'opening_times' => $validated['opening_times'],
                'closing_times' => $validated['closing_times'],
            ],
        ]);

        return LocationResource::make($location);
    }

    public function show(Location $location): LocationResource
    {
        return LocationResource::make($location);
    }

    public function update(UpdateLocationRequest $request, Location $location): JsonResponse
    {
        $validated = $request->validated();

        [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ] = LocationQuery::findOrCreateByPostcode($validated['postcode']);

        $location->update([
            'postcode' => $validated['postcode'],
            'latitude' => $latitude,
            'longitude' => $longitude,
            'times' => [
                'opening_times' => $validated['opening_times'],
                'closing_times' => $validated['closing_times'],
            ],
        ]);

        return (new LocationResource($location))
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(Location $location): Response
    {
        $location->delete();

        return response()->noContent();
    }
}
