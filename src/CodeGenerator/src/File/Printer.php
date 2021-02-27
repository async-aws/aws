<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer as BasePrinter;

/**
 * Convert a class definition to a file string.
 *
 * @internal
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Printer extends BasePrinter
{
    /**
     * @var string
     */
    protected $indentation = '    ';

    /**
     * @var int
     */
    protected $linesBetweenMethods = 1;

    public function printClass(ClassType $class, PhpNamespace $namespace = null): string
    {
        $methods = $class->getMethods();
        ksort($methods);
        $class->setMethods($methods);

        return parent::printClass($class, $namespace);
    }
}
