<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class StructureShape extends Shape
{
    public function getMembers()
    {
        return $this->data['members'];
    }
}
