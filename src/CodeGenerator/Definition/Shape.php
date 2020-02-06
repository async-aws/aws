<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class Shape implements \ArrayAccess
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Closure
     */
    private $shapeLocator;

    private function __construct()
    {
    }

    public static function create(string $name, array $data, \Closure $shapeLocator): Shape
    {
        switch ($data['type']) {
            case 'structure':
                $shape = new StructureShape();

                break;
            case 'list':
                $shape = new ListShape();

                break;
            default:
                $shape = new self();
        }

        $shape->name = $name;
        $shape->data = $data;
        $shape->shapeLocator = $shapeLocator;

        return $shape;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->data['type'];
    }
}
