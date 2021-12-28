<?php

namespace JurianArie\UnauthorisedDetection;

use Illuminate\Routing\Route;
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
            return new ReflectionFunction($this->route->getAction()['uses']);
        }

        $controller = $this->route->getController();

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
        $length = $endLine - $startLine;

        $source = file($reflection->getFileName());

        return implode('', array_slice($source, $startLine, $length));
    }
}
