<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection\Detectors;

use JurianArie\UnauthorisedDetection\Endpoint;

final class DetectsIgnoredEndpoints implements DetectsAuthorization
{
    /**
     * Check if the endpoint should be ignored.
     *
     * @param \JurianArie\UnauthorisedDetection\Endpoint $endpoint
     *
     * @return bool
     */
    public function isAuthorized(Endpoint $endpoint): bool
    {
        /** @var array<int, string> $ignoredRoutes */
        $ignoredRoutes = config('unauthorized-detection.ignore');

        return $ignoredRoutes !== []
            && (in_array($endpoint->route()->getName(), $ignoredRoutes)
                || in_array($endpoint->route()->uri, $ignoredRoutes)
                || in_array($endpoint->route()->getAction('controller'), $ignoredRoutes));
    }
}
