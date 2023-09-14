<?php

use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\{withToken};

beforeEach(
    fn () => $this->location = Location::factory()->create([
        'postcode' => 'SW1A 1AA',
        'latitude' => 51.501009,
        'longitude' => -0.141588,
        'times' => [
            'opening_times' => [
                'monday' => '09:00',
            ],
            'closing_times' => [
                'monday' => '17:00',
            ],
        ]
    ])
);

beforeEach(
    fn () => Http::fake([
        'api.postcodes.io/*' => Http::response([
            'status' => 200,
            'result' => [
                'postcode' => 'CV1 4JP',
                'latitude' => 52.400997,
                'longitude' => -1.508122,
            ],
        ]),
    ])
);

describe('fetch', function () {
    beforeEach(fn () => Location::factory(4)->create());

    it('can get locations', fn () => test()
        ->getJson(route('api.v1.locations.index'))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'postcode',
                    'latitude',
                    'longitude',
                    'times' => [
                        'opening_times' => [],
                        'closing_times' => [],
                    ],
                    'created_at',
                    'updated_at',
                ]
            ]
        ])
        ->assertJson([
            'data' => [
                [
                    'id' => $this->location->id,
                    'postcode' => $this->location->postcode,
                    'latitude' => $this->location->latitude,
                    'longitude' => $this->location->longitude,
                    'times' => $this->location->times,
                    'created_at' => $this->location->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $this->location->updated_at->format('Y-m-d H:i:s'),
                ]
            ]
        ])
        ->assertJsonCount(5, 'data'));

    it('can get locations with distance', fn () => test()
        ->getJson(route('api.v1.locations.index', ['postcode' => 'CV1 4JP']))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'postcode',
                    'latitude',
                    'longitude',
                    'distance',
                    'times' => [
                        'opening_times' => [],
                        'closing_times' => [],
                    ],
                    'created_at',
                    'updated_at',
                ]
            ]
        ])
        ->assertJsonCount(5, 'data'));

    it('can get nearest location', fn () => test()
        ->getJson(route('api.v1.locations.nearest', ['postcode' => $this->location->postcode]))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'postcode',
                'latitude',
                'longitude',
                'distance',
                'times' => [
                    'opening_times' => [],
                    'closing_times' => [],
                ],
            ]
        ]));

    it('can get a specific location', fn () => test()
        ->getJson(route('api.v1.locations.show', ['location' => $this->location]))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'postcode',
                'latitude',
                'longitude',
                'times' => [
                    'opening_times' => [],
                    'closing_times' => [],
                ],
            ]
        ])
        ->assertJson([
            'data' => [
                'id' => $this->location->id,
                'postcode' => $this->location->postcode,
                'latitude' => $this->location->latitude,
                'longitude' => $this->location->longitude,
                'times' => $this->location->times,
            ]
        ]));

    it('can not get a specific location with invalid id', fn () => test()
        ->getJson(route('api.v1.locations.show', ['location' => 999]))
        ->assertNotFound());
})->group('locations');

describe('auth crud', function () {
    beforeEach(fn () => $this->unsavedLocation = Location::factory()->make([
        [
            'postcode' => 'CV1 4JP',
            'times' => [
                'opening_times' => [
                    'tuesday' => '09:00',
                ],
                'closing_times' => [
                    'tuesday' => '17:00',
                ],
            ]
        ]
    ]));
    beforeEach(fn () => $this->user = User::factory()->create());
    beforeEach(fn () => withToken($this->token = $this->user->createToken('test-token')->plainTextToken));

    it('can create a new location when authenticated', fn () => test()
        ->postJson(route('api.v1.locations.store'), [
            'postcode' => $this->unsavedLocation->postcode,
            'opening_times' => $this->unsavedLocation->times['opening_times'],
            'closing_times' => $this->unsavedLocation->times['closing_times'],
        ])
        ->assertCreated()
        ->assertJson([
            'data' => [
                'postcode' => $this->unsavedLocation->postcode,
                'times' => $this->unsavedLocation->times,
            ]
        ]));

    it('can not create a new location when unauthenticated', fn () => test()
        ->withoutToken()
        ->postJson(route('api.v1.locations.store'), [
            'postcode' => $this->unsavedLocation->postcode,
            'opening_times' => $this->unsavedLocation->times['opening_times'],
            'closing_times' => $this->unsavedLocation->times['closing_times'],
        ])
        ->assertUnauthorized());

    it('can not create a new location with invalid data and authenticated', fn () => test()
        ->postJson(route('api.v1.locations.store'), [
            'postcode' => 'invalid',
            'opening_times' => 'invalid',
            'closing_times' => 'invalid',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'postcode',
            'opening_times',
            'closing_times',
        ]));

    it('can update a location when authenticated', fn () => test()
        ->putJson(route('api.v1.locations.update', ['location' => $this->location]), [
            'postcode' => $this->unsavedLocation->postcode,
            'opening_times' => $this->unsavedLocation->times['opening_times'],
            'closing_times' => $this->unsavedLocation->times['closing_times'],
        ])
        ->assertOk()
        ->assertJson([
            'data' => [
                'postcode' => $this->unsavedLocation->postcode,
                'times' => $this->unsavedLocation->times,
            ]
        ]));

    it('can not update a location when unauthenticated', fn () => test()
        ->withoutToken()
        ->putJson(route('api.v1.locations.update', ['location' => $this->location]), [
            'postcode' => $this->unsavedLocation->postcode,
            'opening_times' => $this->unsavedLocation->times['opening_times'],
            'closing_times' => $this->unsavedLocation->times['closing_times'],
        ])
        ->assertUnauthorized());

    it('can not update a location with invalid data when authenticated', fn () => test()
        ->putJson(route('api.v1.locations.update', ['location' => $this->location]), [
            'postcode' => 'invalid',
            'opening_times' => 'invalid',
            'closing_times' => 'invalid',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'postcode',
            'opening_times',
            'closing_times',
        ]));

    it('can destroy a location when authenticated', fn () => test()
        ->deleteJson(route('api.v1.locations.destroy', [
            'location' => $this->location,
        ]))
        ->assertNoContent());

    it('can not destroy a location when unauthenticated', fn () => test()
        ->withoutToken()
        ->deleteJson(route('api.v1.locations.destroy', [
            'location' => $this->location,
        ]))
        ->assertUnauthorized());
})->group('locations', 'auth');
