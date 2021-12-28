<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use ReflectionClass;
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
                if (str_starts_with($middleware, $am)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isAuthorizedViaFormRequest(): bool
    {
        // TODO: handle closures.
        if ($this->route->getActionMethod() === 'Closure') {
            return false;
        }

        $controller = $this->route->getController();
        $method = $this->route->getActionMethod();

        $this->route->getActionMethod();

        if (get_class($controller) === $method) {
            $method = '__invoke';
        }

        try {
            $parameters = (new ReflectionMethod($controller, $method))->getParameters();

            foreach ($parameters as $parameter) {
                $type = $parameter->getType();
                $class = new ReflectionClass($type->getName());

                if (
                    $class->isSubclassOf(FormRequest::class)
                    && $class->hasMethod('authorize')
                ) {
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
        // TODO: handle closures.
        if ($this->route->getActionMethod() === 'Closure') {
            return false;
        }

        $controller = $this->route->getController();
        $method = $this->route->getActionMethod();
        $authorizingMethods = config('unauthorized-detection.authorization-methods');

        if (get_class($controller) === $method) {
            $method = '__invoke';
        }

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
