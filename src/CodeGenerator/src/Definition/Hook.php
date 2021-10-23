<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class Hook
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getFilters(): array
    {
        return $this->data['filters'] ?? [];
    }

    public function getAction(): ?string
    {
        return $this->data['action']['type'] ?? null;
    }

    public function getOptions(): array
    {
        return $this->data['action']['options'] ?? [];
    }
}
