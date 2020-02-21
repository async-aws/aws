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
    private $name;

    private $definition;

    private $documentation;

    private $pagination;

    private $waiter;

    public function __construct(string $name, array $definition, array $documentation, array $pagination, array $waiter)
    {
        $this->name = $name;
        $this->definition = $definition;
        $this->documentation = $documentation;
        $this->pagination = $pagination;
        $this->waiter = $waiter;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOperation(string $name): ?Operation
    {
        if (isset($this->definition['operations'][$name])) {
            return Operation::create(
                $this->definition['operations'][$name] + [
                    '_documentation' => $this->documentation['operations'][$name] ?? null,
                    '_apiVersion' => $this->definition['metadata']['apiVersion'],
                ],
                $this,
                $this->getPagination($name),
                $this->createClosureToFindShape()
            );
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

    public function getSignatureVersion(): string
    {
        return $this->definition['metadata']['signatureVersion'];
    }

    public function getEndpointPrefix(): string
    {
        return $this->definition['metadata']['endpointPrefix'];
    }

    private function getPagination(string $name): ?Pagination
    {
        if (isset($this->pagination['pagination'][$name])) {
            return Pagination::create($this->pagination['pagination'][$name]);
        }

        return null;
    }

    private function getShape(string $name, ?Member $member, array $extra): ?Shape
    {
        if (isset($this->definition['shapes'][$name])) {
            $documentation = null;
            if ($member instanceof StructureMember) {
                $documentation = $this->documentation['shapes'][$name]['refs'][$member->getOwnerShape()->getName() . '$' . $member->getName()] ?? null;
            }

            return Shape::create($name, $this->definition['shapes'][$name] + ['_documentation' => $documentation] + $extra, $this->createClosureToFindShape());
        }

        return null;
    }

    private function createClosureToFindShape(): \Closure
    {
        $definition = $this;

        return \Closure::fromCallable(function (string $name, Member $member = null, array $extra = []) use ($definition) {
            return $definition->getShape($name, $member, $extra);
        });
    }

    private function createClosureToFindOperation(): \Closure
    {
        $definition = $this;

        return \Closure::fromCallable(function (string $name) use ($definition): ?Operation {
            return $definition->getOperation($name);
        });
    }
}
