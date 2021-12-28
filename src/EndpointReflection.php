<?php

namespace JurianArie\UnauthorisedDetection;

use Closure;
use Illuminate\Routing\Route;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use Reflector;

class EndpointReflection
{
    private Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * Instantiate a reflection object for the endpoint.
     *
     * @return \ReflectionMethod|\ReflectionFunction
     *
     * @throws \ReflectionException
     */
    public function getReflection(): Reflector
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

        return new ReflectionMethod($controller, $method);
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
        $reflection = $this->getReflection();

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
}
