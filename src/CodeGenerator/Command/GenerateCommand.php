<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Command;

use AsyncAws\CodeGenerator\Generator\ApiGenerator;
use AsyncAws\CodeGenerator\Generator\ServiceDefinition;
use PhpCsFixer\Config;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Error\ErrorsManager;
use PhpCsFixer\Runner\Runner;
use PhpCsFixer\ToolInfo;
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
            new InputOption('raw', 'r', InputOption::VALUE_NONE, 'Do not run php-cs-fixer. Option for debugging purpose.'),
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
        $serviceNames = $this->getServiceNames($input->getArgument('service'), $input->getOption('all'), $io, $manifest['services']);
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
            $paginationArray = \json_decode(\file_get_contents($manifest['services'][$serviceName]['pagination']), true);
            if (\count($serviceNames) > 1) {
                $operationNames = $this->getOperationNames(null, true, $io, $definitionArray, $manifest['services'][$serviceName]);
            } else {
                $operationNames = $this->getOperationNames($input->getArgument('operation'), $input->getOption('all'), $io, $definitionArray, $manifest['services'][$serviceName]);
            }
            if (\is_int($operationNames)) {
                return $operationNames;
            }

            $progressOperation->start(\count($operationNames));

            $definition = new ServiceDefinition($definitionArray, $documentationArray, $paginationArray);
            $baseNamespace = $manifest['services'][$serviceName]['namespace'] ?? \sprintf('AsyncAws\\%s', $serviceName);
            $resultNamespace = $baseNamespace . '\\Result';

            foreach ($operationNames as $operationName) {
                $progressOperation->setMessage($operationName);
                $progressOperation->advance();
                $progressOperation->display();

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

            if (!$input->getOption('raw')) {
                $progressOperation->setMessage('Fixing CS');
                $progressOperation->display();
                $this->fixCS($baseNamespace, $serviceName, $io);
            }

            $progressOperation->finish();
        }

        if ($drawProgressService) {
            $progressService->finish();
        }

        if ($operationCounter > 1) {
            $io->success($operationCounter . ' operations generated');
        } else {
            $io->success('Operation generated');
        }

        return 0;
    }

    /**
     * @return array|int
     */
    private function getServiceNames(?string $inputServiceName, bool $returnAll, SymfonyStyle $io, array $manifest)
    {
        if ($inputServiceName) {
            if (!isset($manifest[$inputServiceName])) {
                $io->error(\sprintf('Could not find service named "%s".', $inputServiceName));

                return 1;
            }

            return [$inputServiceName];
        }

        $services = array_keys($manifest);
        if ($returnAll) {
            return $services;
        }

        $allServices = '<all services>';
        $serviceName = $io->choice('Select the service to generate', \array_merge([$allServices], $services));
        if ($serviceName === $allServices) {
            return $services;
        }

        return [$serviceName];
    }

    /**
     * @return array|int
     */
    private function getOperationNames(?string $inputOperationName, bool $returnAll, SymfonyStyle $io, array $definition, array $manifest)
    {
        if ($inputOperationName) {
            if ($returnAll) {
                $io->error(\sprintf('Cannot use "--all" together with an operation. You passed "%s" as operation.', $inputOperationName));

                return 1;
            }

            if (!isset($definition['operations'][$inputOperationName])) {
                $io->error(\sprintf('Could not find operation named "%s".', $inputOperationName));

                return 1;
            }

            if (!isset($manifest['methods'][$inputOperationName])) {
                $io->warning(\sprintf('Operation named "%s" has never been generated.', $inputOperationName));
                if (!$io->confirm('Do you want adding it?', true)) {
                    return 1;
                }
            }

            return [$inputOperationName];
        }

        $operations = array_keys($manifest['methods']);
        if ($returnAll) {
            return $operations;
        }

        $newOperation = '<new operation>';
        $allOperations = '<all operation>';

        $operationName = $io->choice('Select the operation to generate', \array_merge([$allOperations, $newOperation], $operations));
        if ($operationName === $allOperations) {
            return $operations;
        }
        if ($operationName === $newOperation) {
            return [$io->choice('Select the operation to generate', array_diff(\array_keys($definition['operations']), \array_keys($manifest['methods'])))];
        }

        return [$operationName];
    }

    private function getOperationConfig(array $manifest, string $service, string $operationName): array
    {
        $default = [
            'generate-method' => true,
            'separate-result-trait' => false,
            'generate-result' => true,
        ];

        return array_merge(
            $default,
            $manifest['services'][$service]['methods'][$operationName] ?? []
        );
    }

    private function fixCs(string $baseNamespace, string $serviceName, SymfonyStyle $io): void
    {
        $reflection = new \ReflectionClass(\sprintf('%s\\%sClient', $baseNamespace, $serviceName));
        $path = \dirname($reflection->getFileName());

        // assert this
        $baseDir = \dirname($this->manifestFile);
        if (!\file_exists($baseDir . '/.php_cs')) {
            $io->warning('Unable to run php-cs-fixer. Please define a .php_cs file alongside the manifest.json file');

            return;
        }

        /** @var Config $config */
        $resolver = new ConfigurationResolver(
            new Config(),
            [
                'config' => $baseDir . '/.php_cs',
                'allow-risky' => true,
                'dry-run' => false,
                'path' => [$path],
                'path-mode' => 'override',
                'using-cache' => true,
                'cache-file' => $baseDir . '/.php_cs.cache',
                'diff' => false,
                'stop-on-violation' => false,
            ],
            $baseDir,
            new ToolInfo()
        );

        $runner = new Runner(
            $resolver->getFinder(),
            $resolver->getFixers(),
            $resolver->getDiffer(),
            null,
            new ErrorsManager(),
            $resolver->getLinter(),
            $resolver->isDryRun(),
            $resolver->getCacheManager(),
            $resolver->getDirectory(),
            $resolver->shouldStopOnViolation()
        );
        $runner->fix();
    }
}
