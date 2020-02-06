<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

/**
 * A wrapper for the service definition array.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ServiceDefinition
{
    private $definition;

    private $documentation;

    private $pagination;

    public function __construct(array $definition, array $documentation, array $pagination)
    {
        $this->definition = $definition;
        $this->documentation = $documentation;
        $this->pagination = $pagination;
    }

    public function getOperation(string $name): ?Operation
    {
        if (isset($this->definition['operations'][$name])) {
            return Operation::create($this->definition['operations'][$name], $this->createClosureToFindShapes());
        }

        return null;
    }

    public function getOperationDocumentation(string $name): ?string
    {
        return $this->documentation['operations'][$name] ?? null;
    }

    public function getOperationPagination(string $name): ?array
    {
        return $this->pagination['pagination'][$name] ?? null;
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

    public function getShape(string $name): ?Shape
    {
        if (isset($this->definition['shapes'][$name])) {
            return Shape::create($name, $this->definition['shapes'][$name], $this->createClosureToFindShapes());
        }

        return null;
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

    private function createClosureToFindShapes(): \Closure
    {
        $definition = $this;

        return \Closure::fromCallable(function (string $name) use ($definition) {
            return $definition->getShape($name);
        });
    }
}
