<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as RouteFacade;

class Detector
{
    public function unauthorizedEndpoints(): Collection
    {
        return (new Collection (RouteFacade::getRoutes()->getRoutes()))
            ->filter(function (Route $route): bool {
                $endpoint = new Endpoint($route);

                return !$endpoint->isAuthorized();
            });
    }
}