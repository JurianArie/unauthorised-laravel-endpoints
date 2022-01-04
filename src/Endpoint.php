<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

use Illuminate\Routing\Route;

class Endpoint
{
    /**
     * The route instance.
     *
     * @var \Illuminate\Routing\Route $route
     */
    private Route $route;

    /**
     * The endpoint reflection instance.
     *
     * @var \JurianArie\UnauthorisedDetection\EndpointReflection $endpointReflection
     */
    private EndpointReflection $endpointReflection;

    /**
     * Instantiate a new Endpoint.
     *
     * @param \Illuminate\Routing\Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * The route instance.
     *
     * @return \Illuminate\Routing\Route
     */
    public function route(): Route
    {
        return $this->route;
    }

    /**
     * Check if the endpoint is authorised.
     *
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return false;
    }

    /**
     * Get the (cached) reflection of the endpoint.
     *
     * @return \JurianArie\UnauthorisedDetection\EndpointReflection
     */
    public function endpointReflection(): EndpointReflection
    {
        return $this->endpointReflection ??= new EndpointReflection($this->route);
    }
}
