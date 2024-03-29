<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

/**
 * @internal
 */
class MapShape extends Shape
{
    public function getValue(): MapValue
    {
        return new MapValue($this->data['value'], $this->shapeLocator);
    }

    public function getKey(): MapKey
    {
        return new MapKey($this->data['key'], $this->shapeLocator);
    }

    public function isFlattened(): bool
    {
        return $this->data['flattened'] ?? false;
    }
}
