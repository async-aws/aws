<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

use AsyncAws\CodeGenerator\File\Location\DirectoryResolver;

/**
 * Update composer.json requirements.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class ComposerWriter
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
     * @param array<string, string> $requirements
     */
    public function setRequirements(string $namespace, array $requirements, bool $clean): void
    {
        $filename = $this->findFile($namespace);
        $content = json_decode(file_get_contents($filename), true);
        if ($clean) {
            unset(
                $content['require']['ext-json'], // Older versions of the code generator were using that case. We keep cleaning it to avoid garbage.
                $content['require']['ext-dom'],
                $content['require']['ext-SimpleXML'], // Older versions of the code generator were using that case. We keep cleaning it to avoid garbage.
                $content['require']['ext-simplexml'],
                $content['require']['ext-filter'],
                $content['require']['async-aws/core'],
                $content['require']['symfony/polyfill-uuid'],
            );
        }
        $content['require'] = $requirements + $content['require'];
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
        $dir = $this->directoryResolver->resolveDirectory($namespace);
        while (!file_exists($dir . '/composer.json')) {
            $dir = \dirname($dir);
        }

        return $dir . '/composer.json';
    }
}
