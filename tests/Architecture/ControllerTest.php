<?php

test('controllers should be classes and not be final')
    ->expect('App\Http\Controllers')
    ->toBeClasses()
    ->not->toBeFinal();
