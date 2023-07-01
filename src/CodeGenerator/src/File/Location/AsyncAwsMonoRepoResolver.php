<?php

namespace AsyncAws\CodeGenerator\File\Location;

/**
 * @internal
 */
class AsyncAwsMonoRepoResolver implements DirectoryResolver
{
    /**
     * @var string
     */
    private $srcDirectory;

    public function __construct(string $srcDirectory)
    {
        $this->srcDirectory = $srcDirectory;
    }

    public function resolveDirectory(string $namespace): string
    {
        $relativeNamespace = substr($namespace, \strlen('AsyncAws\\'));

        $parts = explode('\\', $relativeNamespace);
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

        return sprintf('%s/%s', $this->srcDirectory, implode('/', $parts));
    }
}
