<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\Naming;

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
        $this->name = ucfirst($name);
    }

    public static function create(string $namespace, string $name): self
    {
        return new self($namespace, self::safeClassName($name));
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

    /**
     * Make sure we dont use a class name like Trait or Object.
     */
    private static function safeClassName(string $name): string
    {
        if (\in_array($name, ['Object', 'Class', 'Trait'])) {
            return 'Aws' . $name;
        }

        return $name;
    }
}
