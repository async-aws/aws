<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class Shape implements \ArrayAccess
{
    protected $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function create(array $data)
    {
        switch ($data['type']) {
            case 'structure':
                return new StructureShape($data);
            case 'list':
                return new ListShape($data);
            default:
                return new self($data);
        }
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
        throw new \LogicException('Shapes are read only.');
    }

    public function offsetUnset($offset)
    {
        throw new \LogicException('Shapes are read only.');
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
