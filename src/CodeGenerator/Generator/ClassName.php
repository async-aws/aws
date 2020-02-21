<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class ClassName
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $namespace;

    private function __construct(string $namespace, string $name)
    {
        $this->namespace = $namespace;
        $this->name = $name;
    }

    public static function create(string $namespace, string $name): self
    {
        return new self($namespace, GeneratorHelper::safeClassName($name));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getFqdn(): string
    {
        return $this->namespace . '\\' . $this->name;
    }
}
