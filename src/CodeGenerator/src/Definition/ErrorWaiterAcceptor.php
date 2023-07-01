<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

/**
 * @internal
 */
class ErrorWaiterAcceptor extends WaiterAcceptor
{
    public function getError(): ExceptionShape
    {
        return ($this->shapeLocator)($this->data['expected']);
    }
}
