<?php

declare(strict_types=1);

namespace AsyncAws\Build\Command;

use AsyncAws\Build\Generator\ApiGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Create a new API client method and result class.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class CreateCommand extends Command
{
    protected static $defaultName = 'create';

    /**
     * @var string
     */
    private $manifestFile;

    /**
     * @var ApiGenerator
     */
    private $generator;

    public function __construct(string $manifestFile, ApiGenerator $generator)
    {
        $this->manifestFile = $manifestFile;
        $this->generator = $generator;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setAliases(['new']);
        $this->setDescription('Create a API client method.');
        $this->setDefinition([
            new InputArgument('service', InputArgument::REQUIRED),
            new InputArgument('operation', InputArgument::REQUIRED),
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $manifest = \json_decode(\file_get_contents($this->manifestFile), true);
        if (!isset($manifest['services'][$service = $input->getArgument('service')])) {
            $io->error(\sprintf('Service "%s" does not exist in manifest.json', $service));

            return 1;
        }

        $definition = \json_decode(\file_get_contents($manifest['services'][$service]['source']), true);
        $operationName = $input->getArgument('operation');
        if (!isset($definition['operations'][$operationName])) {
            $io->error(\sprintf('Could not find operation named "%s".', $operationName));

            return 1;
        }

        $lastGenerated = $manifest['services'][$service]['methods'][$operationName]['generated'] ?? null;
        if (null !== $lastGenerated) {
            $io->error(\sprintf('Operation named "%s" has already been generated at %s.', $operationName, $lastGenerated));

            return 1;
        }

        $baseNamespace = \sprintf('AsyncAws\\%s', $service);
        $this->generator->generateOperation($definition, $service, $operationName);
        $this->generator->generateResultClass($definition['shapes'], $service, $baseNamespace . '\\Result', $definition['operations'][$operationName]['output']['shape'], $definition['operations'][$operationName]['output']['resultWrapper'] ?? null, true);

        // Update manifest file
        $manifest['services'][$service]['methods'][$operationName]['generated'] = \date('c');
        \file_put_contents($this->manifestFile, \json_encode($manifest, \JSON_PRETTY_PRINT));

        return 0;
    }
}
