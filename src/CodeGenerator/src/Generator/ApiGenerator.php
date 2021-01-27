<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ApiGenerator
{
    private $classRegistry;

    public function __construct(ClassRegistry $classRegistry = null)
    {
        $this->classRegistry = $classRegistry ?? new ClassRegistry();
    }

    public function service(string $baseNamespace, array $managedOperations): ServiceGenerator
    {
        return new ServiceGenerator($this->classRegistry, $baseNamespace, $managedOperations);
    }

    public function getUpdatedClasses(): iterable
    {
        return $this->classRegistry->getRegisteredClasses();
    }
}
