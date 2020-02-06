<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class ListShape extends Shape
{
    public function getMember()
    {
        return $this->data['member'];
    }
}
