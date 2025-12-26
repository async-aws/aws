<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

/**
 * @internal
 */
class Shape
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Closure(string, Member|null=, array<string, mixed>=): Shape
     */
    protected $shapeLocator;

    /**
     * @var \Closure(): ServiceDefinition
     */
    protected $serviceLocator;

    /**
     * @var string
     */
    private $name;

    private function __construct()
    {
    }

    /**
     * @param \Closure(string, Member|null=, array<string, mixed>=): Shape $shapeLocator
     * @param \Closure(): ServiceDefinition                                $serviceLocator
     */
    public static function create(string $name, array $data, \Closure $shapeLocator, \Closure $serviceLocator): Shape
    {
        switch ($data['type']) {
            case 'structure':
                if ($data['exception'] ?? false) {
                    $shape = new ExceptionShape();
                } elseif ($data['document'] ?? false) {
                    $shape = new DocumentShape();
                } elseif ($data['union'] ?? false) {
                    $shape = new UnionShape();
                } else {
                    $shape = new StructureShape();
                }

                break;
            case 'list':
                $shape = new ListShape();

                break;
            case 'map':
                $shape = new MapShape();

                break;
            default:
                $shape = new self();
        }

        $shape->name = $name;
        $shape->data = $data;
        $shape->shapeLocator = $shapeLocator;
        $shape->serviceLocator = $serviceLocator;

        return $shape;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDocumentationMain(): ?string
    {
        return $this->data['_documentation_main'] ?? null;
    }

    public function getDocumentationMember(): ?string
    {
        return $this->data['_documentation_member'] ?? null;
    }

    public function getType(): string
    {
        return $this->data['type'];
    }

    /**
     * @return string[]
     */
    public function getEnum(): array
    {
        return $this->data['enum'] ?? [];
    }

    public function get(string $name): ?string
    {
        return $this->data[$name] ?? null;
    }

    public function getService(): ServiceDefinition
    {
        return ($this->serviceLocator)();
    }
}
