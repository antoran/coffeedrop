<?php

test('models should be classes and not be final')
    ->expect('App\Models')
    ->toBeClasses()
    ->not->toBeFinal();

