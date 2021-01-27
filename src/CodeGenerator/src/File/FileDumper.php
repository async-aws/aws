<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

use AsyncAws\Core\Exception\RuntimeException;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * Dump and validate a php file.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FileDumper
{
    private $phpBin;

    public function dump(string $filename, string $content)
    {
        \file_put_contents($filename . '.errored', "<?php\n\n" . $content);

        $this->verifyFileSyntax($filename . '.errored');
    }

    private function verifyFileSyntax(string $filename): void
    {
        if (null === $this->phpBin) {
            $executableFinder = new PhpExecutableFinder();
            $this->phpBin = $executableFinder->find(false);
            $this->phpBin = false === $this->phpBin ? null : array_merge([$this->phpBin], $executableFinder->findArguments());
        }

        $process = new Process(\array_merge($this->phpBin, ['-l', $filename]));
        $process->run();

        if ($process->isSuccessful()) {
            // Remove backup and cleanup errored files
            $this->moveFile($filename, \substr($filename, 0, -\strlen('.errored')));

            return;
        }

        throw new RuntimeException(sprintf('Could not generate file "%s" due invalid syntax.' . "\n\n%s", $filename, $process->getErrorOutput()));
    }

    private function moveFile(string $from, string $to): void
    {
        if (file_exists($from)) {
            rename($from, $to);
        }
    }
}
