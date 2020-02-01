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

    public function __construct(array $definition)
    {
        $this->definition = $definition;
    }

    public function getOperations(): array
    {
        return $this->definition['operations'] ?? [];
    }

    public function getOperation(string $name): ?array
    {
        return $this->definition['operations'][$name] ?? null;
    }

    public function getShapes(): array
    {
        return $this->definition['shapes'] ?? [];
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
