<?php

declare(strict_types=1);

namespace AsyncAws\Build\Command;

use AsyncAws\Build\Generator\ApiGenerator;
use AsyncAws\Build\Generator\ServiceDefinition;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Update a existing response class or API client method
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class RegenerateCommand extends Command
{
    protected static $defaultName = 'regenerate';

    /**
     * @var string
     */
    private $manifestFile;

    public function __construct(string $manifestFile, ApiGenerator $generator)
    {
        $this->manifestFile = $manifestFile;
        $this->generator = $generator;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setAliases(['update']);
        $this->setDescription('Regenerate or update a API client method.');
        $this->setDefinition([
            new InputArgument('service', InputArgument::REQUIRED),
            new InputArgument('operation', InputArgument::OPTIONAL),
            new InputOption('all', null, InputOption::VALUE_NONE, 'Regenerate all operation in the service')
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

        $definitionArray = \json_decode(\file_get_contents($manifest['services'][$service]['source']), true);
        $operationNames = $this->getOperationNames($input, $io, $definitionArray, $manifest['services'][$service]);
        if (\is_int($operationNames)) {
            return $operationNames;
        }

        $definition = new ServiceDefinition($definitionArray);
        $baseNamespace = \sprintf('AsyncAws\\%s', $service);
        $resultNamespace = $baseNamespace . '\\Result';

        foreach ($operationNames as $operationName) {
            $operation = $definition->getOperation($operationName);
            $operationConfig = $manifest['services'][$service]['methods'][$operationName];
            $resultClassName = $operation['output']['shape'];

            if (!isset($operationConfig['generate-method']) || false !== $operationConfig['generate-method']) {
                $this->generator->generateOperation($definition, $service, $operationName);
            }

            if (!isset($operationConfig['generate-result-trait']) || false !== $operationConfig['generate-result-trait']) {
                $this->generator->generateOutputTrait($definition, $operationName, $resultNamespace, $resultClassName);
            }

            if (!isset($operationConfig['generate-result']) || false !== $operationConfig['generate-result']) {
                $this->generator->generateResultClass($definition, $service, $resultNamespace, $resultClassName, true);
            }

            // Update manifest file
            $manifest['services'][$service]['methods'][$operationName]['generated'] = \date('c');
            \file_put_contents($this->manifestFile, \json_encode($manifest, \JSON_PRETTY_PRINT));
        }

        return 0;
    }

    /**
     * @return array|int
     */
    private function getOperationNames(InputInterface $input, SymfonyStyle $io, array $definition, array $manifest)
    {
        if ($operationName = $input->getArgument('operation')) {
            if ($input->getOption('all')) {
                $io->error(\sprintf('Cannot use "--all" together with an operation. You passed "%s" as operation.', $operationName));

                return 1;
            }

            if (!isset($definition['operations'][$operationName])) {
                $io->error(\sprintf('Could not find operation named "%s".', $operationName));

                return 1;
            }

            $lastGenerated = $manifest['methods'][$operationName]['generated'] ?? null;
            if (null === $lastGenerated) {
                $io->error(\sprintf('Operation named "%s" has never been generated.', $operationName));

                return 1;
            }

            return [$operationName];
        }

        if ($input->getOption('all')) {
            return array_keys($manifest['methods']);
        }

        $io->error('You must specify an operation or use option "--all"');

        return 1;
    }
}
