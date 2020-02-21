<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\File\FileWriter;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ApiGenerator
{
    /**
     * @var FileWriter
     */
    private $fileWriter;

    public function __construct(string $srcDirectory)
    {
        $this->fileWriter = new FileWriter($srcDirectory);
    }

    public function client(NamespaceRegistry $namespaceRegistry): ClientGenerator
    {
        return new ClientGenerator($namespaceRegistry, $this->fileWriter);
    }

    public function operation(NamespaceRegistry $namespaceRegistry, InputGenerator $inputGenerator, ResultGenerator $resultGenerator, PaginationGenerator $pagination): OperationGenerator
    {
        return new OperationGenerator($namespaceRegistry, $inputGenerator, $resultGenerator, $pagination, $this->fileWriter);
    }

    public function waiter(NamespaceRegistry $namespaceRegistry, InputGenerator $inputGenerator): WaiterGenerator
    {
        return new WaiterGenerator($namespaceRegistry, $inputGenerator, $this->fileWriter);
    }

    public function pagination(NamespaceRegistry $namespaceRegistry, InputGenerator $inputGenerator, ResultGenerator $resultGenerator): PaginationGenerator
    {
        return new PaginationGenerator($namespaceRegistry, $inputGenerator, $resultGenerator, $this->fileWriter);
    }

    public function result(NamespaceRegistry $namespaceRegistry): ResultGenerator
    {
        return new ResultGenerator($namespaceRegistry, $this->fileWriter);
    }

    public function input(NamespaceRegistry $namespaceRegistry): InputGenerator
    {
        return new InputGenerator($namespaceRegistry, $this->fileWriter);
    }
}
