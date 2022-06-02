<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection\Detectors;

use JurianArie\UnauthorisedDetection\Endpoint;

final class DetectsUnauthenticatedRoutes implements DetectsAuthorization
{
    /**
     * Check if the endpoint is behind a login.
     *
     * @param \JurianArie\UnauthorisedDetection\Endpoint $endpoint
     *
     * @return bool
     */
    public function isAuthorized(Endpoint $endpoint): bool
    {
        $gatheredMiddleware = $endpoint->route()->gatherMiddleware();
        /** @var array<int, string> $middlewareToCheck */
        $middlewareToCheck = config('unauthorized-detection.authentication-middleware');

        foreach ($middlewareToCheck as $middleware) {
            if (in_array($middleware, $gatheredMiddleware)) {
                return false;
            }
        }

        return true;
    }
}
