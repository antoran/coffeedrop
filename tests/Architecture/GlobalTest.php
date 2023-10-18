<?php

test('should not use debug functions')
    ->expect([
        'dd',
        'ddd',
        'dump',
        'die',
        'var_dump',
        'print_r',
        'var_export',
        'xdebug_var_dump',
        'ray',
        'debug',
    ])
    ->not->toBeUsed();
