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
        '/\$this->authorize\(\'(.*)\\)/',
        '/Gate::authorize\(\'(.*)\\)/',
    ],

    /**
     * Ignore routes by detecting them using Request::is() and Request::routeIs().
     */
    'ignore' => [
        '\Illuminate\Routing\RedirectController',
        '\Illuminate\Routing\ViewController',
    ],

    /**
     * The classes that are used for unauthorized detection.
     */
    'authorization-detectors' => [
        \JurianArie\UnauthorisedDetection\Detectors\DetectsIgnoredEndpoints::class,
        \JurianArie\UnauthorisedDetection\Detectors\DetectsUnauthenticatedRoutes::class,
        \JurianArie\UnauthorisedDetection\Detectors\DetectsAuthorizationInMiddleware::class,
        \JurianArie\UnauthorisedDetection\Detectors\DetectsAuthorizationInFormRequest::class,
        \JurianArie\UnauthorisedDetection\Detectors\DetectsAuthorizationInSourceCode::class,
    ],
];
