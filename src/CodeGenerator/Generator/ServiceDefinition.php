<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

/**
 * A wrapper for the service definition array.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ServiceDefinition
{
    private $definition;

    private $documentation;

    public function __construct(array $definition, array $documentation)
    {
        $this->definition = $definition;
        $this->documentation = $documentation;
    }

    public function getOperations(): array
    {
        return $this->definition['operations'] ?? [];
    }

    public function getOperation(string $name): ?array
    {
        return $this->definition['operations'][$name] ?? null;
    }

    public function getOperationDocumentation(string $name): ?string
    {
        return $this->documentation['operations'][$name] ?? null;
    }

    public function getShapes(): array
    {
        return $this->definition['shapes'] ?? [];
    }

    public function getShapesDocumentation(): array
    {
        return $this->documentation['shapes'] ?? [];
    }

    public function getParameterDocumentation(string $className, string $parameter, string $type): ?string
    {
        if (\array_key_exists("$className\$$parameter", $this->documentation['shapes'][$type]['refs'] ?? [])) {
            return $this->documentation['shapes'][$type]['refs']["$className\$$parameter"];
        }

        throw new \InvalidArgumentException(\sprintf('Missing documentation for %s::$%s of type %s', $className, $parameter, $type));
    }

    public function getShape(string $name): ?array
    {
        return $this->definition['shapes'][$name] ?? null;
    }

    public function getVersion(): string
    {
        return $this->definition['version'];
    }

    public function getMetadata(): array
    {
        return $this->definition['metadata'];
    }

    public function getServiceId(): string
    {
        return $this->definition['metadata']['serviceId'];
    }

    public function getApiVersion(): string
    {
        return $this->definition['metadata']['apiVersion'];
    }

    public function getSignatureVersion(): string
    {
        return $this->definition['metadata']['signatureVersion'];
    }

    public function getEndpointPrefix(): string
    {
        return $this->definition['metadata']['endpointPrefix'];
    }

    public function getGlobalEndpoint(): string
    {
        return $this->definition['metadata']['globalEndpoint'];
    }
}
