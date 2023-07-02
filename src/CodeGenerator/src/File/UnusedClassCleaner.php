<?php

namespace AsyncAws\CodeGenerator\File;

use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\Location\DirectoryResolver;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use Symfony\Component\Finder\Finder;

/**
 * @internal
 */
final class UnusedClassCleaner
{
    /**
     * @var DirectoryResolver
     */
    private $directoryResolver;

    public function __construct(DirectoryResolver $directoryResolver)
    {
        $this->directoryResolver = $directoryResolver;
    }

    /**
     * @param iterable<ClassBuilder> $generatedClasses
     */
    public function cleanUnusedClasses(NamespaceRegistry $namespaceRegistry, iterable $generatedClasses): void
    {
        $usedClassNames = [];
        foreach ($generatedClasses as $class) {
            $usedClassNames[$class->getClassName()->getFqdn()] = true;
        }

        foreach ($this->getCleanableNamespaces($namespaceRegistry) as $className) {
            $namespace = $className->getNamespace();
            $directory = $this->directoryResolver->resolveDirectory($namespace);

            if (!is_dir($directory)) {
                continue;
            }

            $finder = (new Finder())
                ->name('*.php')
                ->files()
                ->in($directory);

            foreach ($finder as $file) {
                $fileClassName = $namespace . '\\' . str_replace('/', '\\', substr($file->getRelativePathname(), 0, -4));

                if (isset($usedClassNames[$fileClassName])) {
                    continue;
                }

                unlink($file->getPathname());
            }
        }
    }

    /**
     * @return iterable<ClassName>
     */
    private function getCleanableNamespaces(NamespaceRegistry $namespaceRegistry): iterable
    {
        $fakeLocator = function () {
            throw new \BadMethodCallException('Unexpected call on the fake shape');
        };

        $fakeStructureShape = Shape::create('AsyncAwsFakeShape', ['type' => 'structure'], $fakeLocator, $fakeLocator);
        \assert($fakeStructureShape instanceof StructureShape);

        yield $namespaceRegistry->getEnum(Shape::create('AsyncAwsFakeShape', ['type' => 'string'], $fakeLocator, $fakeLocator));
        yield $namespaceRegistry->getException($fakeStructureShape);
        yield $namespaceRegistry->getInput($fakeStructureShape);
        yield $namespaceRegistry->getResult($fakeStructureShape);
        yield $namespaceRegistry->getObject($fakeStructureShape);
    }
}
