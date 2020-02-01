<?php

declare(strict_types=1);

namespace AsyncAws\Build;

use AsyncAws\Core\Exception\RuntimeException;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;

/**
 * Takes a namespace definition and create a file form it.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FileWriter
{
    /**
     * @var string
     */
    private $srcDirectory;
    private $printer;

    public function __construct(string $srcDirectory)
    {
        $this->srcDirectory = $srcDirectory;
        $this->printer = new PsrPrinter();
    }

    public function write(PhpNamespace $namespace)
    {
        $fqcn = $namespace->getName();
        // Remove AsyncAws\
        $fqcn = substr($fqcn, 9);

        $classes = $namespace->getClasses();
        $class = $classes[array_key_first($classes)];
        $className = $class->getName();

        $directory = \sprintf('%s/%s', $this->srcDirectory, str_replace('\\', '/', $fqcn));
        if (!is_dir($directory)) {
            if (false === mkdir($directory, 0777, true)) {
                throw new RuntimeException(sprintf('Could not create directory "%s"', $directory));
            }
        }

        $filename = \sprintf('%s/%s.php', $directory, $className);
        \file_put_contents($filename, "<?php\n\n" . $this->printer->printNamespace($namespace));
    }
}
