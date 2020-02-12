<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

use AsyncAws\Core\Exception\RuntimeException;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * Takes a namespace definition and create a file form it.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FileWriter
{
    private $printer;

    private $srcDirectory;

    private $phpBin;

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
                throw new \RuntimeException(sprintf('Could not create directory "%s"', $directory));
            }
        }

        $filename = \sprintf('%s/%s.php', $directory, $className);

        \file_put_contents($filename . '.errored', "<?php\n\n" . $this->printer->printNamespace($namespace));

        $this->verifyFileSyntax($filename . '.errored');
    }

    /**
     * Delete a class from disk.
     */
    public function deleteClass(string $class): void
    {
        // Remove AsyncAws\
        $class = substr($class, 9);
        $file = \sprintf('%s/%s.php', $this->srcDirectory, str_replace('\\', '/', $class));
        if (is_file($file)) {
            unlink($file);
        }
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

        throw new RuntimeException(sprintf('Could not generate file "%s" due invalid syntax.' . "\n\n%s", $filename, $process->getOutput()));
    }

    private function moveFile(string $from, string $to): void
    {
        if (file_exists($from)) {
            rename($from, $to);
        }
    }
}
