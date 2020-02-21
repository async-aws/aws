<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\File\FileWriter;
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
     * @var InputGenerator
     */
    private $input;

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
        return $this->operation ?? $this->operation = new OperationGenerator($this->namespaceRegistry, $this->input(), $this->result(), $this->pagination(), $this->fileWriter);
    }

    public function waiter(): WaiterGenerator
    {
        return $this->waiter ?? $this->waiter = new WaiterGenerator($this->namespaceRegistry, $this->input(), $this->fileWriter);
    }

    public function pagination(): PaginationGenerator
    {
        return $this->pagination ?? $this->pagination = new PaginationGenerator($this->namespaceRegistry, $this->input(), $this->result(), $this->fileWriter);
    }

    public function result(): ResultGenerator
    {
        return $this->result ?? $this->result = new ResultGenerator($this->namespaceRegistry, $this->fileWriter);
    }

    public function input(): InputGenerator
    {
        return $this->input ?? $this->input = new InputGenerator($this->namespaceRegistry, $this->fileWriter);
    }
}
