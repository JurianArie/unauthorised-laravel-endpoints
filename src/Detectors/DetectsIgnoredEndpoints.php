<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection\Detectors;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
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
        /** @var array<int, string> $ignoredEndpoints */
        $ignoredEndpoints = config('unauthorized-detection.ignore');

        $request = Request::create($endpoint->route()->uri);
        $request->setRouteResolver(fn (): Route => $endpoint->route()->bind($request));

        foreach ($ignoredEndpoints as $ignoredEndpoint) {
            if ($request->is($ignoredEndpoint) || $request->routeIs($ignoredEndpoint)) {
                return true;
            }
        }

        return false;
    }
}
