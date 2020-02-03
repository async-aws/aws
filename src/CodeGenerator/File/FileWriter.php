<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

use AsyncAws\Core\Exception\RuntimeException;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Takes a namespace definition and create a file form it.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FileWriter
{
    private $printer;

    /**
     * @var string
     */
    private $srcDirectory;

    public function __construct(string $srcDirectory)
    {
        $this->srcDirectory = $srcDirectory;
        $this->printer = new Printer();
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

    /**
     * Delete a class from disk.
     */
    public function delete(string $class): void
    {
        // Remove AsyncAws\
        $class = substr($class, 9);
        $file = \sprintf('%s/%s.php', $this->srcDirectory, str_replace('\\', '/', $class));
        if (is_file($file)) {
            unlink($file);
        }
    }
}
