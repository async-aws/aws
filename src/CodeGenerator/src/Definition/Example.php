<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class Example
{
    /**
     * @var array
     */
    private $data;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $operation = new self();
        $operation->data = $data;

        return $operation;
    }

    public function getInput(): ?array
    {
        return $this->data['input'] ?? null;
    }

    public function getOutput(): ?array
    {
        return $this->data['output'] ?? null;
    }
}
