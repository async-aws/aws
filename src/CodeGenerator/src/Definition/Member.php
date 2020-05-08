<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class Member
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Closure
     */
    protected $shapeLocator;

    public function __construct(array $data, \Closure $shapeLocator)
    {
        $this->data = $data;
        $this->shapeLocator = $shapeLocator;
    }

    public function getShape(): Shape
    {
        return ($this->shapeLocator)($this->data['shape'], $this);
    }

    public function getLocationName(): ?string
    {
        return $this->data['locationName'] ?? null;
    }

    public function getQueryName(): ?string
    {
        return $this->data['queryName'] ?? null;
    }

    public function isFlattened(): ?bool
    {
        return $this->data['flattened'] ?? false;
    }

    public function isDeprecated(): ?bool
    {
        return $this->data['deprecated'] ?? false;
    }
}
