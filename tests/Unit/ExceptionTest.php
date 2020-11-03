<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit;

use AsyncAws\Core\Exception\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ExceptionTest extends TestCase
{
    public function testNamespacedExceptions()
    {
        $finder = new Finder();
        $root = \dirname(__DIR__, 2);
        $finder
            ->files()
            ->in($root . '/src/Core/src')
            ->in($root . '/src/Integration/*/*/src')
            ->in($root . '/src/Service/*/src')
            ->name('*.php');

        foreach ($finder as $file) {
            $contents = $file->getContents();
            $path = $file->getRealPath();
            $relativePath = substr($path, \strlen($root) + 1);

            $hasInvalidException = strpos($contents, 'throw new \\');
            $snippet = '';
            if (false !== $hasInvalidException) {
                $snippet = substr($contents, $hasInvalidException, 60);
            }

            self::assertFalse($hasInvalidException, sprintf('File "%s" must throw an exception implementing "%s". [..] %s [..]', $relativePath, Exception::class, $snippet));
        }
    }
}
