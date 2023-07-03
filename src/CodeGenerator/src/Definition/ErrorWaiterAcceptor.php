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
        $shape = ($this->shapeLocator)($this->data['expected']);

        if (!$shape instanceof ExceptionShape) {
            throw new \InvalidArgumentException(sprintf('The error "%s" of the waiter acceptor should have an Exception shape.', $this->data['expected']));
        }

        return $shape;
    }
}
