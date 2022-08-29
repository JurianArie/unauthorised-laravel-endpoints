<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

use Closure;
use Illuminate\Routing\Route;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use Reflector;

class Endpoint
{
    /**
     * The route instance.
     *
     * @var \Illuminate\Routing\Route $route
     */
    private Route $route;

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
     * Get reflection parameters for the endpoint.
     *
     * @return \ReflectionParameter[]
     *
     * @throws \ReflectionException
     */
    public function reflectionParameters(): array
    {
        return $this->reflector()->getParameters();
    }

    /**
     * Get the source code of the endpoint.
     *
     * @return string
     *
     * @throws \ReflectionException
     */
    public function sourceCode(): string
    {
        return $this->sourceCodeOf($this->reflector());
    }

    /**
     * Get the source code of the reflector.
     *
     * @param \Reflector $reflection
     * @return string
     */
    public function sourceCodeOf(Reflector $reflection): string
    {
        if (
            !$reflection instanceof ReflectionFunctionAbstract
            && ! $reflection instanceof ReflectionClass
        ) {
            return '';
        }

        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();
        $fileName = $reflection->getFileName();

        if ($fileName === false || $startLine === false || $endLine === false) {
            return '';
        }

        $source = file($fileName);

        if ($source === false) {
            return '';
        }

        return implode('', array_slice($source, $startLine, $endLine - $startLine));
    }

    /**
     * Instantiate a reflection object for the endpoint.
     *
     * @return \ReflectionMethod|\ReflectionFunction
     *
     * @throws \ReflectionException
     */
    private function reflector(): Reflector
    {
        $method = $this->route->getActionMethod();

        if ($method === 'Closure') {
            $closure = $this->route->getAction('uses');

            if (!$closure instanceof Closure) {
                throw new ReflectionException('Could not create a reflection object for the closure.');
            }

            return new ReflectionFunction($closure);
        }

        $controller = $this->route->getController();

        if (!is_string($controller) && !is_object($controller)) {
            throw new ReflectionException('Could not create a reflection object for the controller.');
        }

        $controllerClassName = is_object($controller)
            ? get_class($controller)
            : $controller;

        if ($controllerClassName === $method) {
            $method = '__invoke';
        }

        return new ReflectionMethod($controller, $method);
    }
}
