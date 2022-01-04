<?php

return [
    /**
     * Any middleware that can be used for authentication.
     */
    'authentication-middleware' => [
        'auth',
    ],

    /**
     * Any middleware that can be used for authorization.
     */
    'authorization-middleware' => [
        'can:',
        'Laravel\Horizon\Http\Middleware\Authenticate',
    ],

    /**
     * Any method that can be used for authorization.
     */
    'authorization-methods' => [
        '$this->authorize(',
        'Gate::authorize(',
    ],

    /**
     * Any route that you want to be excluded from unauthorized detection.
     * This can be the name, uri or action of the route.
     */
    'ignore' => [],
];
