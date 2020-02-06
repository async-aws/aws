<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class Operation implements \ArrayAccess
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var \Closure
     */
    private $shapeLocator;

    private function __construct()
    {
    }

    public static function create(array $data, \Closure $shapeLocator): self
    {
        $operation = new self();
        $operation->data = $data;
        $operation->shapeLocator = $shapeLocator;

        return $operation;
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    public function getOutput(): ?Shape
    {
        if (isset($this->data['output']['shape'])) {
            $find = $this->shapeLocator;

            return $find($this->data['output']['shape']);
        }

        return null;
    }

    public function getInput(): ?Shape
    {
        if (isset($this->data['input']['shape'])) {
            $find = $this->shapeLocator;

            return $find($this->data['input']['shape']);
        }

        return null;
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Operations are read only.');
    }

    public function offsetUnset($offset)
    {
        throw new \LogicException('Operations are read only.');
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getOutputResultWrapper(): ?string
    {
        return $this->data['output']['resultWrapper'] ?? null;
    }
}
