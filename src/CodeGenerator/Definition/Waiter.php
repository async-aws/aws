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

    private function __construct()
    {
    }

    public static function create(array $data, \Closure $operationLocator): self
    {
        $waiter = new self();
        $waiter->data = $data;
        $waiter->operationLocator = $operationLocator;

        return $waiter;
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
        return \array_map([WaiterAcceptor::class, 'create'], $this->data['acceptors']);
    }

    public function getDelay(): float
    {
        return (float) $this->data['delay'];
    }

    public function getMaxAttempts(): int
    {
        return $this->data['maxAttempts'];
    }

    public function getOperation(): ?Operation
    {
        if (isset($this->data['operation'])) {
            return ($this->operationLocator)($this->data['operation']);
        }

        return null;
    }
}
