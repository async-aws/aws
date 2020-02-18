<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

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
}
