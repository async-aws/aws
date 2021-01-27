<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;

/**
 * Takes a namespace definition and create a file form it.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ClassWriter
{
    private $srcDirectory;

    private $fileDumper;

    private $printer;

    public function __construct(string $srcDirectory, FileDumper $fileDumper)
    {
        $this->srcDirectory = $srcDirectory;
        $this->fileDumper = $fileDumper;
        $this->printer = new Printer();
    }

    public function write(ClassBuilder $classBuilder)
    {
        $content = $this->printer->printNamespace($classBuilder->build());

        $className = $classBuilder->getClassName();
        // Remove AsyncAws\
        $fqcn = substr($className->getNamespace(), 9);

        $className = $className->getName();
        $directory = $this->resolveDirectory($fqcn);

        $filename = \sprintf('%s/%s.php', $directory, $className);

        $this->fileDumper->dump($filename, $content);
    }

    private function resolveDirectory(string $fqcn): string
    {
        $parts = explode('\\', $fqcn);
        $service = array_shift($parts); // Lambda, S3, Sqs etc
        if (isset($parts[0]) && 'Tests' === $parts[0]) {
            array_shift($parts); // Tests
            array_unshift($parts, $service, 'tests');
        } else {
            array_unshift($parts, $service, 'src');
        }
        if ('Core' !== $service) {
            array_unshift($parts, 'Service');
        }

        $directory = \sprintf('%s/%s', $this->srcDirectory, implode('/', $parts));
        if (!is_dir($directory)) {
            if (false === mkdir($directory, 0777, true)) {
                throw new \RuntimeException(sprintf('Could not create directory "%s"', $directory));
            }
        }

        return $directory;
    }
}
