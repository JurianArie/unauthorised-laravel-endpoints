<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection\Detectors;

use JurianArie\UnauthorisedDetection\Endpoint;

final class DetectsAuthorizationInMiddleware implements DetectsAuthorization
{
    /**
     * Check if the endpoint is authorised via middleware.
     * This could be route or controller middleware.
     *
     * @param \JurianArie\UnauthorisedDetection\Endpoint $endpoint
     *
     * @return bool
     */
    public function isAuthorized(Endpoint $endpoint): bool
    {
        $gatheredMiddleware = $endpoint->route()->gatherMiddleware();
        /** @var array<int, string> $authorizingMiddleware */
        $authorizingMiddleware = config('unauthorized-detection.authorization-middleware');

        foreach ($gatheredMiddleware as $middleware) {
            foreach ($authorizingMiddleware as $am) {
                if (str_starts_with($middleware, $am)) {
                    return true;
                }
            }
        }

        return false;
    }
}
