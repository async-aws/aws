<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
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

    public function client(ServiceDefinition $definition): ClientGenerator
    {
        return new ClientGenerator($this->fileWriter, $definition);
    }

    public function operation(ServiceDefinition $definition): OperationGenerator
    {
        return new OperationGenerator($this->fileWriter, $definition);
    }

    public function result(): ResultGenerator
    {
        return new ResultGenerator($this->fileWriter);
    }
}
