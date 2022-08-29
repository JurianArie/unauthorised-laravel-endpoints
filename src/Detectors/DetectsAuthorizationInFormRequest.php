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
                $formRequest = new ReflectionClass($className);

                if (
                    $formRequest->isSubclassOf(FormRequest::class)
                    && $formRequest->hasMethod('authorize')
                    && $this->authorizeDoesntDirectlyReturnsTrue($endpoint, $formRequest)
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
     * Checks if the authorize method of the form request doesn't directly return true.
     *
     * @param \JurianArie\UnauthorisedDetection\Endpoint $endpoint
     * @param \ReflectionClass<\Illuminate\Foundation\Http\FormRequest> $formRequest
     * @return bool
     * @throws \ReflectionException
     */
    private function authorizeDoesntDirectlyReturnsTrue(Endpoint $endpoint, ReflectionClass $formRequest): bool
    {
        $sourceCode = $endpoint->sourceCodeOf($formRequest);

        return preg_match(
            '/public function authorize\(\).*\n    \{\n        return true\;\n    \}/',
            $sourceCode
        ) === 0;
    }
}
