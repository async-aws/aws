<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ServiceGenerator
{
    /**
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

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

    public function __construct(FileWriter $fileWriter, string $baseNamespace)
    {
        $this->fileWriter = $fileWriter;
        $this->namespaceRegistry = new NamespaceRegistry($baseNamespace);
    }

    public function client(): ClientGenerator
    {
        return $this->client ?? $this->client = new ClientGenerator($this->namespaceRegistry, $this->fileWriter);
    }

    public function operation(): OperationGenerator
    {
        return $this->operation ?? $this->operation = new OperationGenerator($this->namespaceRegistry, $this->input(), $this->result(), $this->pagination(), $this->test(), $this->fileWriter, $this->type());
    }

    public function waiter(): WaiterGenerator
    {
        return $this->waiter ?? $this->waiter = new WaiterGenerator($this->namespaceRegistry, $this->input(), $this->fileWriter, $this->type());
    }

    public function pagination(): PaginationGenerator
    {
        return $this->pagination ?? $this->pagination = new PaginationGenerator($this->namespaceRegistry, $this->input(), $this->result(), $this->fileWriter, $this->type());
    }

    public function test(): TestGenerator
    {
        return $this->test ?? $this->test = new TestGenerator($this->namespaceRegistry, $this->fileWriter);
    }

    public function result(): ResultGenerator
    {
        return $this->result ?? $this->result = new ResultGenerator($this->namespaceRegistry, $this->fileWriter, $this->object(), $this->type(), $this->enum());
    }

    public function input(): InputGenerator
    {
        return $this->input ?? $this->input = new InputGenerator($this->namespaceRegistry, $this->fileWriter, $this->object(), $this->type(), $this->enum());
    }

    public function type(): TypeGenerator
    {
        return $this->type ?? $this->type = new TypeGenerator($this->namespaceRegistry);
    }

    public function enum(): EnumGenerator
    {
        return $this->enum ?? $this->enum = new EnumGenerator($this->namespaceRegistry, $this->fileWriter);
    }

    public function object(): ObjectGenerator
    {
        return $this->object ?? $this->object = new ObjectGenerator($this->namespaceRegistry, $this->fileWriter, $this->type(), $this->enum());
    }
}
