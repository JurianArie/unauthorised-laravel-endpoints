<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection\Detectors;

use JurianArie\UnauthorisedDetection\Endpoint;

interface DetectsAuthorization
{
    /**
     * Check if the endpoint is authorized.
     *
     * @param \JurianArie\UnauthorisedDetection\Endpoint $endpoint
     *
     * @return bool
     */
    public function isAuthorized(Endpoint $endpoint): bool;
}
