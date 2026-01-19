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
    public function __construct()
    {
        parent::__construct();

        $this->indentation = '    ';
        $this->linesBetweenMethods = 1;
        $this->wrapLength = 1000;
    }

    /**
     * @param ClassType $class
     */
    public function printClass($class, ?PhpNamespace $namespace = null): string
    {
        $methods = $class->getMethods();
        ksort($methods);
        /** @phpstan-ignore-next-line */
        $class->setMethods($methods);

        return parent::printClass($class, $namespace);
    }
}
