<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Generator\CodeGenerator\PopulatorGenerator;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\CodeGenerator\Generator\ResponseParser\ParserProvider;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class ServiceGenerator
{
    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var list<string>
     */
    private $managedOperations;

    /**
     * @var ?ClientGenerator
     */
    private $client;

    /**
     * @var ?OperationGenerator
     */
    private $operation;

    /**
     * @var ?WaiterGenerator
     */
    private $waiter;

    /**
     * @var ?PaginationGenerator
     */
    private $pagination;

    /**
     * @var ?ResultGenerator
     */
    private $result;

    /**
     * @var ?TestGenerator
     */
    private $test;

    /**
     * @var ?InputGenerator
     */
    private $input;

    /**
     * @var ?EnumGenerator
     */
    private $enum;

    /**
     * @var ?HookGenerator
     */
    private $hook;

    /**
     * @var ?ObjectGenerator
     */
    private $object;

    /**
     * @var ?ShapeUsageHelper
     */
    private $shapeUsage;

    /**
     * @var ?TypeGenerator
     */
    private $type;

    /**
     * @var ClassRegistry
     */
    private $classRegistry;

    /**
     * @var RequirementsRegistry
     */
    private $requirementsRegistry;

    /**
     * @var ?ExceptionGenerator
     */
    private $exception;

    /**
     * @var ?ParserProvider
     */
    private $parserProvider;

    /**
     * @var ?PopulatorGenerator
     */
    private $populator;

    /**
     * @param list<string> $managedOperations
     */
    public function __construct(ClassRegistry $classRegistry, RequirementsRegistry $requirementsRegistry, string $baseNamespace, array $managedOperations)
    {
        $this->classRegistry = $classRegistry;
        $this->requirementsRegistry = $requirementsRegistry;
        $this->managedOperations = $managedOperations;
        $this->namespaceRegistry = new NamespaceRegistry($baseNamespace);
    }

    public function client(): ClientGenerator
    {
        return $this->client ?? $this->client = new ClientGenerator($this->classRegistry, $this->namespaceRegistry, $this->requirementsRegistry);
    }

    public function operation(): OperationGenerator
    {
        return $this->operation ?? $this->operation = new OperationGenerator($this->classRegistry, $this->namespaceRegistry, $this->requirementsRegistry, $this->input(), $this->result(), $this->pagination(), $this->test(), $this->exception(), $this->type());
    }

    public function waiter(): WaiterGenerator
    {
        return $this->waiter ?? $this->waiter = new WaiterGenerator($this->classRegistry, $this->namespaceRegistry, $this->input(), $this->exception(), $this->parserProvider(), $this->type());
    }

    public function pagination(): PaginationGenerator
    {
        return $this->pagination ?? $this->pagination = new PaginationGenerator($this->classRegistry, $this->namespaceRegistry, $this->input(), $this->result(), $this->type());
    }

    public function test(): TestGenerator
    {
        return $this->test ?? $this->test = new TestGenerator($this->classRegistry, $this->namespaceRegistry);
    }

    public function populator(): PopulatorGenerator
    {
        return $this->populator ?? $this->populator = new PopulatorGenerator($this->classRegistry, $this->namespaceRegistry, $this->requirementsRegistry, $this->object(), $this->shapeUsage(), $this->type(), $this->enum(), $this->parserProvider());
    }

    public function result(): ResultGenerator
    {
        return $this->result ?? $this->result = new ResultGenerator($this->classRegistry, $this->namespaceRegistry, $this->populator());
    }

    public function exception(): ExceptionGenerator
    {
        return $this->exception ?? $this->exception = new ExceptionGenerator($this->classRegistry, $this->namespaceRegistry, $this->populator());
    }

    public function parserProvider(): ParserProvider
    {
        return $this->parserProvider ?? $this->parserProvider = new ParserProvider($this->namespaceRegistry, $this->requirementsRegistry, $this->type());
    }

    public function input(): InputGenerator
    {
        return $this->input ?? $this->input = new InputGenerator($this->classRegistry, $this->namespaceRegistry, $this->requirementsRegistry, $this->object(), $this->shapeUsage(), $this->type(), $this->enum(), $this->hook());
    }

    public function type(): TypeGenerator
    {
        return $this->type ?? $this->type = new TypeGenerator($this->namespaceRegistry);
    }

    public function enum(): EnumGenerator
    {
        return $this->enum ?? $this->enum = new EnumGenerator($this->classRegistry, $this->namespaceRegistry, $this->shapeUsage());
    }

    public function hook(): HookGenerator
    {
        return $this->hook ?? $this->hook = new HookGenerator();
    }

    public function object(): ObjectGenerator
    {
        return $this->object ?? $this->object = new ObjectGenerator($this->classRegistry, $this->namespaceRegistry, $this->requirementsRegistry, $this->shapeUsage(), $this->type(), $this->enum());
    }

    public function shapeUsage(): ShapeUsageHelper
    {
        return $this->shapeUsage ?? $this->shapeUsage = new ShapeUsageHelper($this->managedOperations);
    }

    public function getNamespaceRegistry(): NamespaceRegistry
    {
        return $this->namespaceRegistry;
    }
}
