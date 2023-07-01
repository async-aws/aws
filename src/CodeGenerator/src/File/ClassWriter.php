<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

use AsyncAws\CodeGenerator\File\Location\DirectoryResolver;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;

/**
 * Takes a namespace definition and create a file form it.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class ClassWriter
{
    /**
     * @var DirectoryResolver
     */
    private $directoryResolver;

    /**
     * @var FileDumper
     */
    private $fileDumper;

    /**
     * @var Printer
     */
    private $printer;

    public function __construct(DirectoryResolver $directoryResolver, FileDumper $fileDumper)
    {
        $this->fileDumper = $fileDumper;
        $this->printer = new Printer();
        $this->directoryResolver = $directoryResolver;
    }

    public function write(ClassBuilder $classBuilder): void
    {
        $content = $this->printer->printNamespace($classBuilder->build());

        $className = $classBuilder->getClassName();

        $namespace = $className->getNamespace();
        $className = $className->getName();
        $directory = $this->resolveDirectory($namespace);

        $filename = sprintf('%s/%s.php', $directory, $className);

        $this->fileDumper->dump($filename, $content);
    }

    private function resolveDirectory(string $namespace): string
    {
        $directory = $this->directoryResolver->resolveDirectory($namespace);
        if (!is_dir($directory)) {
            if (false === mkdir($directory, 0777, true)) {
                throw new \RuntimeException(sprintf('Could not create directory "%s"', $directory));
            }
        }

        return $directory;
    }
}
