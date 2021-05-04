<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class Waiter
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var \Closure
     */
    private $operationLocator;

    /**
     * @var \Closure
     */
    private $shapeLocator;

    public function __construct(array $data, \Closure $operationLocator, \Closure $shapeLocator)
    {
        $this->data = $data;
        $this->operationLocator = $operationLocator;
        $this->shapeLocator = $shapeLocator;
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    /**
     * @return WaiterAcceptor[]
     */
    public function getAcceptors(): array
    {
        return array_map(function (array $data) {
            return WaiterAcceptor::create($data, $this->shapeLocator);
        }, $this->data['acceptors']);
    }

    public function getDelay(): float
    {
        return (float) $this->data['delay'];
    }

    public function getMaxAttempts(): int
    {
        return $this->data['maxAttempts'];
    }

    public function getOperation(): Operation
    {
        if (!isset($this->data['operation'])) {
            throw new \InvalidArgumentException('The operation is not defined.');
        }

        return ($this->operationLocator)($this->data['operation']);
    }
}
