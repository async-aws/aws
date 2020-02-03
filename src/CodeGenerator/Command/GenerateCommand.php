<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Command;

use AsyncAws\CodeGenerator\Generator\ApiGenerator;
use AsyncAws\CodeGenerator\Generator\ServiceDefinition;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Update a existing response class or API client method.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class GenerateCommand extends Command
{
    protected static $defaultName = 'generate';

    /**
     * @var string
     */
    private $manifestFile;

    private $generator;

    public function __construct(string $manifestFile, ApiGenerator $generator)
    {
        $this->manifestFile = $manifestFile;
        $this->generator = $generator;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setAliases(['update']);
        $this->setDescription('Create or update API client methods.');
        $this->setDefinition([
            new InputArgument('service', InputArgument::OPTIONAL),
            new InputArgument('operation', InputArgument::OPTIONAL),
            new InputOption('all', null, InputOption::VALUE_NONE, 'Update all operations'),
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $progressService = (new SymfonyStyle($input, $output->section()))->createProgressBar();
        $progressService->setFormat(' %current%/%max% [%bar%] %message%');
        $progressService->setMessage('Service');

        $progressOperation = (new SymfonyStyle($input, $output->section()))->createProgressBar();
        $progressOperation->setFormat(' %current%/%max% [%bar%] %message%');
        $progressOperation->setMessage('Operation');

        $manifest = \json_decode(\file_get_contents($this->manifestFile), true);
        $serviceNames = $this->getServiceNames($input, $io, $manifest['services']);
        if (\is_int($serviceNames)) {
            return $serviceNames;
        }

        $drawProgressService = \count($serviceNames) > 1;
        $drawProgressService and $progressService->start(\count($serviceNames));
        $operationCounter = 0;
        foreach ($serviceNames as $serviceName) {
            if ($drawProgressService) {
                $progressService->setMessage($serviceName);
                $progressService->advance();
                $progressService->display();
            }

            $definitionArray = \json_decode(\file_get_contents($manifest['services'][$serviceName]['source']), true);
            $documentationArray = \json_decode(\file_get_contents($manifest['services'][$serviceName]['documentation']), true);
            $operationNames = $this->getOperationNames($input, $io, $definitionArray, $manifest['services'][$serviceName]);
            if (\is_int($operationNames)) {
                return $operationNames;
            }

            $drawProgressOperation = \count($serviceNames) > 1 || \count($operationNames) > 1;
            if ($drawProgressOperation) {
                $progressOperation->start(\count($operationNames));
            }

            $definition = new ServiceDefinition($definitionArray, $documentationArray);
            $baseNamespace = $manifest['services'][$serviceName]['namespace'] ?? \sprintf('AsyncAws\\%s', $serviceName);
            $resultNamespace = $baseNamespace . '\\Result';

            foreach ($operationNames as $operationName) {
                if ($drawProgressOperation) {
                    $progressOperation->setMessage($operationName);
                    $progressOperation->advance();
                }

                $operation = $definition->getOperation($operationName);
                $operationConfig = $this->getOperationConfig($manifest, $serviceName, $operationName);
                $resultClassName = $operation['output']['shape'];

                if ($operationConfig['generate-method']) {
                    $this->generator->generateOperation($definition, $operationName, $serviceName, $baseNamespace);
                }

                if ($operationConfig['generate-result']) {
                    $this->generator->generateResultClass($definition, $operationName, $resultNamespace, $resultClassName, true, $operationConfig['separate-result-trait']);
                }

                // Update manifest file
                if (!isset($manifest['services'][$serviceName]['methods'][$operationName])) {
                    $manifest['services'][$serviceName]['methods'][$operationName] = [];
                }
                \file_put_contents($this->manifestFile, \json_encode($manifest, \JSON_PRETTY_PRINT));
                ++$operationCounter;
            }
        }

        if ($operationCounter > 1) {
            $io->success($operationCounter . ' operations generated');
        } else {
            $io->success('Operation generated');
        }
        $io->note('Don\' forget to run clean the generated code:' . "\n" . 'php-cs-fixer fix ./src');

        return 0;
    }

    /**
     * @return array|int
     */
    private function getServiceNames(InputInterface $input, SymfonyStyle $io, array $manifest)
    {
        if ($serviceName = $input->getArgument('service')) {
            if (!isset($manifest[$serviceName])) {
                $io->error(\sprintf('Could not find service named "%s".', $serviceName));

                return 1;
            }

            return [$serviceName];
        }

        if ($input->getOption('all')) {
            return array_keys($manifest);
        }

        $io->error('You must specify an service or use option "--all"');

        return 1;
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

            if (!isset($manifest['methods'][$operationName])) {
                $io->warning(\sprintf('Operation named "%s" has never been generated.', $operationName));
                if (!$io->confirm('Do you want adding it?', true)) {
                    return 1;
                }
            }

            return [$operationName];
        }

        if ($input->getOption('all')) {
            return array_keys($manifest['methods'] ?? []);
        }

        $io->error('You must specify an operation or use option "--all"');

        return 1;
    }

    private function getOperationConfig(array $manifest, string $service, string $operationName): array
    {
        $default = [
            'generate-method' => true,
            'separate-result-trait' => true,
            'generate-result' => true,
        ];

        return array_merge(
            $default,
            $manifest['services'][$service]['methods'][$operationName] ?? []
        );
    }
}
