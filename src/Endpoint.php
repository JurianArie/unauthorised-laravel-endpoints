<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class Endpoint
{
    private Route $route;
    private EndpointReflection $endpointReflection;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * Check if the endpoint is authorised.
     *
     * @return bool
     */
    public function isAuthorized(): bool
    {
        if ($this->shouldBeIgnored()) {
            return true;
        }

        if (!$this->requiresAuthorization()) {
            return true;
        }

        return $this->isAuthorizedViaMiddleware()
            || $this->isAuthorizedViaFormRequest()
            || $this->isAuthorizedViaAuthorize();
    }

    private function shouldBeIgnored(): bool
    {
        /** @var array<int, string> $ignoredRoutes */
        $ignoredRoutes = config('unauthorized-detection.ignore');

        return $ignoredRoutes !== []
            && (in_array($this->route->getName(), $ignoredRoutes)
                || in_array($this->route->uri, $ignoredRoutes)
                || in_array($this->route->getAction('controller'), $ignoredRoutes));
    }

    /**
     * Check if the endpoint is behind a login.
     *
     * @return bool
     */
    private function requiresAuthorization(): bool
    {
        /** @var array<int, string> $middlewareToCheck */
        $middlewareToCheck = config('unauthorized-detection.authentication-middleware');

        foreach ($middlewareToCheck as $middleware) {
            if (in_array($middleware, $this->route->gatherMiddleware())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the endpoint is authorised via middleware.
     * This could be route or controller middleware.
     *
     * @return bool
     */
    private function isAuthorizedViaMiddleware(): bool
    {
        $gatheredMiddleware = $this->route->gatherMiddleware();
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

    /**
     * Check if the endpoint is authorised via a form request.
     *
     * @return bool
     */
    private function isAuthorizedViaFormRequest(): bool
    {
        try {
            $parameters = $this->endpointReflection()->getReflection()->getParameters();

            foreach ($parameters as $parameter) {
                $type = $parameter->getType();

                if (!$type instanceof ReflectionNamedType) {
                    continue;
                }

                /** @var class-string $className */
                $className = $type->getName();
                $class = new ReflectionClass($className);

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

    /**
     * Check if the endpoint is authorised authorization methods.
     * e.g. Controller::authorize() or Gate::authorize().
     *
     * @return bool
     */
    private function isAuthorizedViaAuthorize(): bool
    {
        /** @var array<int, string> $authorizingMethods */
        $authorizingMethods = config('unauthorized-detection.authorization-methods');

        try {
            $methodSource = $this->endpointReflection()->sourceCode();

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

    /**
     * Get the (cached) reflection of the endpoint.
     *
     * @return \JurianArie\UnauthorisedDetection\EndpointReflection
     */
    private function endpointReflection(): EndpointReflection
    {
        return $this->endpointReflection ??= new EndpointReflection($this->route);
    }
}
