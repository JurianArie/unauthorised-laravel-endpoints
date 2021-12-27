<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use ReflectionException;

class Endpoint
{
    private Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function isAuthorized(): bool
    {
        if (!$this->requiresAuthorization()) {
            return true;
        }

        return $this->isAuthorizedViaMiddleware()
            || $this->isAuthorizedViaFormRequest()
            || $this->isAuthorizedViaAuthorize();
    }

    private function requiresAuthorization(): bool
    {
        $middlewareToCheck = config('unauthorized-detection.authentication-middleware');

        foreach ($middlewareToCheck as $middleware) {
            if (in_array($middleware, $this->route->gatherMiddleware())) {
                return true;
            }
        }

        return false;
    }

    private function isAuthorizedViaMiddleware(): bool
    {
        $gatheredMiddleware = $this->route->gatherMiddleware();
        $authorizingMiddleware = config('unauthorized-detection.authorization-middleware');

        foreach ($gatheredMiddleware as $middleware) {
            foreach ($authorizingMiddleware as $am) {
                if (str_starts_with($am, $middleware)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isAuthorizedViaFormRequest(): bool
    {
        $controller = $this->route->getController();
        $method = $this->route->getActionMethod();

        try {
            $parameters = (new ReflectionMethod($controller, $method))->getParameters();

            foreach ($parameters as $parameter) {
                $class = $parameter->getClass();

                if ($class !== null && $class->isSubclassOf(FormRequest::class)) {
                    return true;
                }
            }

            return false;
        } catch (ReflectionException $e) {
            return false;
        }
    }

    private function isAuthorizedViaAuthorize(): bool
    {
        $controller = $this->route->getController();
        $method = $this->route->getActionMethod();
        $authorizingMethods = config('unauthorized-detection.authorization-methods');

        try {
            $methodSource = (new ReflectionMethod($controller, $method))->source();

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
