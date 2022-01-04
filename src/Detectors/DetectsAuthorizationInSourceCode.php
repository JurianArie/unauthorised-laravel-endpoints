<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection\Detectors;

use JurianArie\UnauthorisedDetection\Endpoint;
use ReflectionException;

final class DetectsAuthorizationInSourceCode implements DetectsAuthorization
{
    /**
     * Check if the endpoint is authorised via authorization methods.
     * e.g. Controller::authorize() or Gate::authorize().
     *
     * @param \JurianArie\UnauthorisedDetection\Endpoint $endpoint
     *
     * @return bool
     */
    public function isAuthorized(Endpoint $endpoint): bool
    {
        /** @var array<int, string> $authorizingMethods */
        $authorizingMethods = config('unauthorized-detection.authorization-methods');

        try {
            $methodSource = $endpoint->endpointReflection()->sourceCode();

            foreach ($authorizingMethods as $am) {
                if (str_contains($methodSource, $am)) {
                    return true;
                }
            }
        } catch (ReflectionException $e) {
            return false;
        }

        return false;
    }
}
