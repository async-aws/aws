<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class Pagination
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

    public function getMoreResults(): ?string
    {
        return $this->data['more_results'] ?? null;
    }

    /**
     * @return string[]
     */
    public function getInputToken(): array
    {
        return (array) ($this->data['input_token'] ?? []);
    }

    /**
     * @return string[]
     */
    public function getOutputToken(): array
    {
        return (array) ($this->data['output_token'] ?? []);
    }

    /**
     * @return string[]
     */
    public function getResultkey(): array
    {
        return (array) ($this->data['result_key'] ?? []);
    }
}
