<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\PhpGenerator;

use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Generate a ClassBuilder and maintains the list of generated classes.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ClassRegistry
{
    private $classFactory;

    private $registered = [];

    public function __construct(ClassFactory $classFactory = null)
    {
        $this->classFactory = $classFactory ?? new ClassFactory();
    }

    public function getRegisteredClasses(): iterable
    {
        return $this->registered;
    }

    public function unregister(string $class): void
    {
        unset($this->registered[$class]);
    }

    public function register(string $class, bool $loadExisting = false): ClassBuilder
    {
        if (isset($this->registered[$class])) {
            return $this->registered[$class];
        }

        if ($loadExisting && class_exists($class)) {
            return $this->registered[$class] = new ClassBuilder($this->classFactory->fromClassReflection(new \ReflectionClass($class)));
        }

        $className = ClassName::createFromFqdn($class);

        $namespace = new PhpNamespace($className->getNamespace());
        $namespace->addClass($className->getName());

        return $this->registered[$class] = new ClassBuilder($namespace);
    }
}
