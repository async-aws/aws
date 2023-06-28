<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ApiGenerator
{
    /**
     * @var ClassRegistry
     */
    private $classRegistry;

    /**
     * @var RequirementsRegistry
     */
    private $requirementsRegistry;

    public function __construct(?ClassRegistry $classRegistry = null, ?RequirementsRegistry $requirementsRegistry = null)
    {
        $this->classRegistry = $classRegistry ?? new ClassRegistry();
        $this->requirementsRegistry = $requirementsRegistry ?? new RequirementsRegistry();
    }

    /**
     * @param list<string> $managedOperations
     */
    public function service(string $baseNamespace, array $managedOperations): ServiceGenerator
    {
        return new ServiceGenerator($this->classRegistry, $this->requirementsRegistry, $baseNamespace, $managedOperations);
    }

    /**
     * @return iterable<ClassBuilder>
     */
    public function getUpdatedClasses(): iterable
    {
        return $this->classRegistry->getRegisteredClasses();
    }

    /**
     * @return array<string, string>
     */
    public function getUpdatedRequirements(): array
    {
        return $this->requirementsRegistry->getRequirements();
    }
}
