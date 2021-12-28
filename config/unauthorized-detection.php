<?php

return [
    'authentication-middleware' => [
        'auth',
    ],

    'authorization-middleware' => [
        'can:',
        'Laravel\Horizon\Http\Middleware\Authenticate',
    ],

    'authorization-methods' => [
        '$this->authorize(',
        'Gate::authorize(',
    ],
];
