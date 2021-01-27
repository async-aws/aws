<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ServiceGenerator
{
    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var array
     */
    private $managedOperations;

    /**
     * @var ClientGenerator
     */
    private $client;

    /**
     * @var OperationGenerator
     */
    private $operation;

    /**
     * @var WaiterGenerator
     */
    private $waiter;

    /**
     * @var PaginationGenerator
     */
    private $pagination;

    /**
     * @var ResultGenerator
     */
    private $result;

    /**
     * @var TestGenerator
     */
    private $test;

    /**
     * @var InputGenerator
     */
    private $input;

    /**
     * @var EnumGenerator
     */
    private $enum;

    /**
     * @var ObjectGenerator
     */
    private $object;

    /**
     * @var TypeGenerator
     */
    private $type;

    private $classRegistry;

    public function __construct(ClassRegistry $classRegistry, string $baseNamespace, array $managedOperations)
    {
        $this->classRegistry = $classRegistry;
        $this->managedOperations = $managedOperations;
        $this->namespaceRegistry = new NamespaceRegistry($baseNamespace);
    }

    public function client(): ClientGenerator
    {
        return $this->client ?? $this->client = new ClientGenerator($this->classRegistry, $this->namespaceRegistry);
    }

    public function operation(): OperationGenerator
    {
        return $this->operation ?? $this->operation = new OperationGenerator($this->classRegistry, $this->namespaceRegistry, $this->input(), $this->result(), $this->pagination(), $this->test(), $this->type());
    }

    public function waiter(): WaiterGenerator
    {
        return $this->waiter ?? $this->waiter = new WaiterGenerator($this->classRegistry, $this->namespaceRegistry, $this->input(), $this->type());
    }

    public function pagination(): PaginationGenerator
    {
        return $this->pagination ?? $this->pagination = new PaginationGenerator($this->classRegistry, $this->namespaceRegistry, $this->input(), $this->result(), $this->type());
    }

    public function test(): TestGenerator
    {
        return $this->test ?? $this->test = new TestGenerator($this->classRegistry, $this->namespaceRegistry);
    }

    public function result(): ResultGenerator
    {
        return $this->result ?? $this->result = new ResultGenerator($this->classRegistry, $this->namespaceRegistry, $this->object(), $this->type(), $this->enum());
    }

    public function input(): InputGenerator
    {
        return $this->input ?? $this->input = new InputGenerator($this->classRegistry, $this->namespaceRegistry, $this->object(), $this->type(), $this->enum());
    }

    public function type(): TypeGenerator
    {
        return $this->type ?? $this->type = new TypeGenerator($this->namespaceRegistry);
    }

    public function enum(): EnumGenerator
    {
        return $this->enum ?? $this->enum = new EnumGenerator($this->classRegistry, $this->namespaceRegistry);
    }

    public function object(): ObjectGenerator
    {
        return $this->object ?? $this->object = new ObjectGenerator($this->classRegistry, $this->namespaceRegistry, $this->managedOperations, $this->type(), $this->enum());
    }
}
