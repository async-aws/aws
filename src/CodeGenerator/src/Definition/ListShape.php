<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class ListShape extends Shape
{
    public function getMember(): ListMember
    {
        return new ListMember($this->data['member'], $this->shapeLocator);
    }

    public function isFlattened(): bool
    {
        return $this->data['flattened'] ?? false;
    }
}
