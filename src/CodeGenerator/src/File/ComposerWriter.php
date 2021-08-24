<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

/**
 * Update composer.json requirements.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ComposerWriter
{
    private $srcDirectory;

    public function __construct(string $srcDirectory)
    {
        $this->srcDirectory = $srcDirectory;
    }

    public function setRequirements(string $namespace, array $requirements, bool $clean): void
    {
        $filename = $this->findFile($namespace);
        $content = json_decode(file_get_contents($filename), true);
        if ($clean) {
            unset(
                $content['require']['ext-json'],
                $content['require']['ext-dom'],
                $content['require']['ext-SimpleXML'],
                $content['require']['ext-filter'],
            );
        }
        $content['require'] += $requirements;
        uksort($content['require'], function ($a, $b) {
            $la = 'php' === $a ? 0 : ('ext-' === substr($a, 0, 4) ? 1 : 2);
            $lb = 'php' === $b ? 0 : ('ext-' === substr($b, 0, 4) ? 1 : 2);
            if ($la === $lb) {
                return $a <=> $b;
            }

            return $la <=> $lb;
        });
        file_put_contents($filename, json_encode($content, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES) . "\n");
    }

    private function findFile(string $namespace): string
    {
        $dir = $this->resolveDirectory($namespace);
        while (!file_exists($dir . '/composer.json')) {
            $dir = \dirname($dir);
        }

        return $dir . '/composer.json';
    }

    private function resolveDirectory(string $fqcn): string
    {
        $parts = explode('\\', $fqcn);
        array_shift($parts); // AsyncAws
        $service = array_shift($parts); // Lambda, S3, Sqs etc
        array_unshift($parts, $service, 'src');
        if ('Core' !== $service) {
            array_unshift($parts, 'Service');
        }

        $directory = sprintf('%s/%s', $this->srcDirectory, implode('/', $parts));
        if (!is_dir($directory)) {
            if (false === mkdir($directory, 0777, true)) {
                throw new \RuntimeException(sprintf('Could not create directory "%s"', $directory));
            }
        }

        return $directory;
    }
}
