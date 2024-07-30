<?php

namespace AsyncAws\CodeGenerator\File\Location;

class StandalonePackageResolver implements DirectoryResolver
{
    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @var string
     */
    private $rootNamespace;

    public function __construct(string $rootDirectory, string $rootNamespace)
    {
        $this->rootDirectory = $rootDirectory;
        $this->rootNamespace = $rootNamespace;
    }

    public function resolveDirectory(string $namespace): string
    {
        if ($namespace === $this->rootNamespace) {
            return $this->rootDirectory . '/src';
        }

        if (0 !== strpos($namespace, $this->rootNamespace . '\\')) {
            throw new \InvalidArgumentException('The namespace must belong to the generated package.');
        }

        $relativeNamespace = substr($namespace, \strlen($this->rootNamespace . '\\'));

        $parts = explode('\\', $relativeNamespace);
        if (isset($parts[0]) && 'Tests' === $parts[0]) {
            array_shift($parts); // Tests
            array_unshift($parts, 'tests');
        } else {
            array_unshift($parts, 'src');
        }

        return \sprintf('%s/%s', $this->rootDirectory, implode('/', $parts));
    }
}
