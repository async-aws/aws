<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Command;

use AsyncAws\CodeGenerator\ChangeLogUpdater;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @internal
 */
#[AsCommand(name: 'add-change-log', description: 'Add a change log entry in all packages containing modified files.')]
class AddChangeLogCommand extends Command
{
    private const CHANGELOG_LABELS = ['BC-BREAK', 'Removed', 'Added', 'Deprecated', 'Dependency bumped', 'Changed', 'Fixed', 'Security'];

    private readonly ChangeLogUpdater $changeLogUpdater;

    public function __construct()
    {
        $this->changeLogUpdater = new ChangeLogUpdater();
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('type', InputArgument::REQUIRED, 'The type of change. Supported values: ' . implode(', ', self::CHANGELOG_LABELS), null, self::CHANGELOG_LABELS);
        $this->addArgument('message', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $errorOutput = $output instanceof ConsoleOutput ? $output->getErrorOutput() : $output;

        if (!\in_array($input->getArgument('type'), self::CHANGELOG_LABELS, true)) {
            $errorOutput->writeln('Invalid type provided. It must be one of: ' . implode(', ', self::CHANGELOG_LABELS) . '.');

            return 1;
        }

        $changedFiles = explode("\n", (new Process(['git', 'ls-files', '--modified']))->mustRun()->getOutput());

        $changedServices = [];
        foreach ($changedFiles as $file) {
            $parts = explode('/', $file);
            if ('src' !== $parts[0]) {
                continue;
            }
            if (!isset($parts[1])) {
                continue;
            }

            if ('Service' === $parts[1]) {
                $service = $parts[2];
                $base = 'src/Service/' . $service;

                if ('.template' === $service) {
                    continue; // The service template does not have an actual changelog
                }
            } elseif ('Integration' === $parts[1]) {
                $service = $parts[2] . '/' . $parts[3];
                $base = 'src/Integration/' . $service;
            } elseif ('CodeGenerator' === $parts[1]) {
                continue; // The code generator does not have a changelog as it has no releases
            } elseif ('Core' === $parts[1]) {
                $service = $parts[1];
                $base = 'src/' . $service;
            } else {
                continue;
            }

            $subPath = substr($file, \strlen($base));
            $normalizedSubPath = str_replace(\DIRECTORY_SEPARATOR, '/', $subPath);

            if (\in_array($normalizedSubPath, ['/README.md', '/Makefile', '/.gitattributes', '/.gitignore', '/LICENSE', '/phpunit.xml.dist', '/roave-bc-check.yaml'], true) || str_starts_with($normalizedSubPath, '/.github/') || str_starts_with($normalizedSubPath, '/tests/')) {
                // Scaffolding files don't require a changelog entry when modified as they don't impact the usage of the packages
                continue;
            }

            if (!isset($changedServices[$service])) {
                $changedServices[$service] = ['base' => $base, 'files' => []];
            }
            $changedServices[$service]['files'][] = $subPath;
        }

        $fixSectionLabel = '### ' . $input->getArgument('type');
        $message = $input->getArgument('message');

        foreach ($changedServices as $service => $info) {
            $newLines = ['- ' . $message];

            $changeLogPath = $info['base'] . '/CHANGELOG.md';
            $this->changeLogUpdater->addNewChangeLogLines($changeLogPath, $service, $fixSectionLabel, $newLines, $errorOutput->writeln(...));
        }

        return 0;
    }
}
