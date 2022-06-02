<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection\Detectors;

use Illuminate\Foundation\Http\FormRequest;
use JurianArie\UnauthorisedDetection\Endpoint;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

final class DetectsAuthorizationInFormRequest implements DetectsAuthorization
{
    /**
     * Check if the endpoint is authorised via a form request.
     *
     * @param \JurianArie\UnauthorisedDetection\Endpoint $endpoint
     *
     * @return bool
     */
    public function isAuthorized(Endpoint $endpoint): bool
    {
        try {
            $parameters = $endpoint->reflectionParameters();

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
}
