<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

class Detector
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected Router $router;

    /**
     * Create a new Detector instance.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * All the routes that are not authorised.
     *
     * @return \Illuminate\Support\Collection<int, \Illuminate\Routing\Route>
     */
    public function unauthorizedEndpoints(): Collection
    {
        return Collection::make($this->router->getRoutes()->getRoutes())
            ->filter(function (Route $route): bool {
                $endpoint = new Endpoint($route);

                return !$endpoint->isAuthorized();
            });
    }
}
