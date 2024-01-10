<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\PhpGenerator;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Factory;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Generate Nette PhpNamespace from existing source class.
 *
 * This is a slightly modified version of \Nette\PhpGenerator\Factory
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class ClassFactory
{
    /**
     * @var Factory
     */
    private $factory;

    public function __construct()
    {
        $this->factory = new Factory();
    }

    /**
     * @param \ReflectionClass<object> $from
     */
    public function fromClassReflection(\ReflectionClass $from): PhpNamespace
    {
        /** @var ClassType $class */
        $class = $this->factory->fromClassReflection($from, true);

        $namespace = new PhpNamespace($from->getNamespaceName());
        $filename = $from->getFileName();
        $rows = file($filename);

        // Find Use statements
        foreach ($rows as $row) {
            if (false !== strstr($row, 'class ' . $from->getName())) {
                // No use statements after this point
                break;
            }

            if (preg_match('#^use ([^;]+)( as [^;]+)?;#im', $row, $match)) {
                $namespace->addUse($match[1], $match[2] ?? null);
            }
        }

        $namespace->add($class);

        return $namespace;
    }
}
