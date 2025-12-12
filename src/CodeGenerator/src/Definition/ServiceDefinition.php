<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

use AsyncAws\CodeGenerator\Generator\GeneratorHelper;

/**
 * A wrapper for the service definition array.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class ServiceDefinition
{
    private const SUPPORTED_PROTOCOLS = ['json', 'rest-json', 'query', 'rest-xml'];

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $endpoints;

    /**
     * @var array
     */
    private $definition;

    /**
     * @var array
     */
    private $documentation;

    /**
     * @var array
     */
    private $pagination;

    /**
     * @var array
     */
    private $waiter;

    /**
     * @var array
     */
    private $example;

    /**
     * @var array
     */
    private $hooks;

    /**
     * @var array<string, list<Hook>>|null
     */
    private $hooksByTarget;

    /**
     * @var string
     */
    private $apiReferenceUrl;

    public function __construct(string $name, array $endpoints, array $definition, array $documentation, array $pagination, array $waiter, array $example, array $hooks, string $apiReferenceUrl)
    {
        $this->name = $name;
        $this->endpoints = $endpoints;
        $this->definition = $definition;
        $this->documentation = $documentation;
        $this->pagination = $pagination;
        $this->waiter = $waiter;
        $this->example = $example;
        $this->apiReferenceUrl = $apiReferenceUrl;
        $this->hooks = $hooks;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEndpoints(): array
    {
        return $this->endpoints;
    }

    public function getOperation(string $name): ?Operation
    {
        if (isset($this->definition['operations'][$name])) {
            return Operation::create(
                $name,
                $this->definition['operations'][$name] + [
                    '_documentation' => $this->documentation['operations'][$name] ?? null,
                    '_apiVersion' => $this->definition['metadata']['apiVersion'],
                    '_method_name' => ucfirst(GeneratorHelper::normalizeName($name)),
                ],
                $this,
                $this->getPagination($name),
                $this->getExample($name),
                $this->createClosureToFindShape()
            );
        }

        return null;
    }

    public function findEndpointOperationName(): ?Operation
    {
        foreach ($this->definition['operations'] as $name => $data) {
            if (isset($data['endpointoperation'])) {
                return $this->getOperation($name);
            }
        }

        return null;
    }

    public function getWaiter(string $name): ?Waiter
    {
        if (isset($this->waiter['waiters'][$name])) {
            return new Waiter($this->waiter['waiters'][$name] + ['name' => $name], $this->createClosureToFindOperation(), $this->createClosureToFindShape());
        }

        return null;
    }

    public function getApiVersion(): string
    {
        return $this->definition['metadata']['apiVersion'];
    }

    public function getEndpointPrefix(): string
    {
        return $this->definition['metadata']['endpointPrefix'];
    }

    public function getTargetPrefix(): string
    {
        return $this->definition['metadata']['targetPrefix'];
    }

    public function getJsonVersion(): float
    {
        return (float) $this->definition['metadata']['jsonVersion'];
    }

    public function getProtocol(): string
    {
        $protocol = $this->definition['metadata']['protocol'];
        if (\in_array($protocol, self::SUPPORTED_PROTOCOLS, true)) {
            return $protocol;
        }

        $protocols = $this->definition['metadata']['protocols'] ?? [];
        foreach ($protocols as $protocol) {
            if (\in_array($protocol, self::SUPPORTED_PROTOCOLS, true)) {
                return $protocol;
            }
        }

        return $protocol;
    }

    public function getApiReferenceUrl(): string
    {
        return $this->apiReferenceUrl;
    }

    /**
     * @return Hook[]
     */
    public function getHooks(string $target): array
    {
        if (null === $this->hooksByTarget) {
            $this->hooksByTarget = [];
            foreach ($this->hooks as $hook) {
                $this->hooksByTarget[$hook['target']][] = new Hook($hook['options']);
            }
        }

        return $this->hooksByTarget[$target] ?? [];
    }

    private function getPagination(string $name): ?Pagination
    {
        if (isset($this->pagination['pagination'][$name])) {
            return Pagination::create($this->pagination['pagination'][$name]);
        }

        return null;
    }

    private function getExample(string $name): Example
    {
        return Example::create($this->example['examples'][$name][0] ?? []);
    }

    /**
     * @param array<string, mixed> $extra
     */
    private function getShape(string $name, ?Member $member, array $extra): Shape
    {
        if (isset($this->definition['shapes'][$name])) {
            $documentationMember = null;
            $documentationMain = $this->documentation['shapes'][$name]['base'] ?? null;
            if ($member instanceof StructureMember) {
                $documentationMember = $this->documentation['shapes'][$name]['refs'][$member->getOwnerShape()->getName() . '$' . $member->getName()] ?? null;
            }

            return Shape::create($name, $this->definition['shapes'][$name] + ['_documentation_main' => $documentationMain, '_documentation_member' => $documentationMember] + $extra, $this->createClosureToFindShape(), $this->createClosureToService());
        }

        throw new \InvalidArgumentException(\sprintf('The shape "%s" does not exist.', $name));
    }

    /**
     * @return \Closure(string, ?Member=, array<string, mixed>=): Shape
     */
    private function createClosureToFindShape(): \Closure
    {
        $definition = $this;

        return \Closure::fromCallable(function (string $name, ?Member $member = null, array $extra = []) use ($definition) {
            return $definition->getShape($name, $member, $extra);
        });
    }

    /**
     * @return \Closure(): self
     */
    private function createClosureToService(): \Closure
    {
        $definition = $this;

        return \Closure::fromCallable(function () use ($definition) {
            return $definition;
        });
    }

    /**
     * @return \Closure(string): Operation
     */
    private function createClosureToFindOperation(): \Closure
    {
        $definition = $this;

        return \Closure::fromCallable(function (string $name) use ($definition): Operation {
            $operation = $definition->getOperation($name);

            if (null === $operation) {
                throw new \InvalidArgumentException(\sprintf('The operation "%s" is not defined.', $name));
            }

            return $operation;
        });
    }
}
