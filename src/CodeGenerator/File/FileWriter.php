<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

use AsyncAws\Core\Exception\RuntimeException;
use Nette\PhpGenerator\PhpNamespace;
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
        $this->copyFile($filename, $filename . '.backup');

        \file_put_contents($filename, "<?php\n\n" . $this->printer->printNamespace($namespace));

        $this->verifyFileSyntax($filename);
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
        $process = new Process(['php', '-l', $filename]);
        $process->run();

        if ($process->isSuccessful()) {
            // Remove backup and cleanup errored files
            $this->removeFile($filename . '.backup');
            $this->removeFile($filename . '.errored');

            return;
        }

        // Move the flawed file.
        $this->copyFile($filename, $filename . '.errored');

        // Try to restore backup
        $this->copyFile($filename . '.backup', $filename);

        throw new RuntimeException(sprintf('Could not generate file "%s" due invalid syntax.' . "\n\n%s", $filename, $process->getOutput()));
    }

    private function removeFile(string $filename): void
    {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    private function copyFile(string $from, string $to): void
    {
        if (file_exists($from)) {
            rename($from, $to);
        }
    }
}
