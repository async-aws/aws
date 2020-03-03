<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Command;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Generator\ApiGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
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
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Update a existing response class or API client method.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class GenerateCommand extends Command
{
    protected static $defaultName = 'generate';

    private $manifest;

    private $manifestFile;

    private $cacheFile;

    private $generator;

    private $cache;

    public function __construct(string $manifestFile, string $cacheFile, ApiGenerator $generator)
    {
        $this->manifestFile = $manifestFile;
        $this->cacheFile = $cacheFile;
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
        $progressService->setFormat(' [%bar%] %message%');
        $progressService->setMessage('Service');

        $progressOperation = (new SymfonyStyle($input, $output->section()))->createProgressBar();
        $progressOperation->setFormat(' [%bar%] %message%');
        $progressOperation->setMessage('Operation');

        $manifest = $this->loadManifest();
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

            $definitionArray = $this->loadFile($manifest['services'][$serviceName]['source'], "$serviceName-source");
            $documentationArray = $this->loadFile($manifest['services'][$serviceName]['documentation'], "$serviceName-documentation");
            $paginationArray = $this->loadFile($manifest['services'][$serviceName]['pagination'], "$serviceName-pagination");
            $waiterArray = isset($manifest['services'][$serviceName]['waiter']) ? $this->loadFile($manifest['services'][$serviceName]['waiter'], "$serviceName-waiter") : ['waiters' => []];
            $exampleArray = isset($manifest['services'][$serviceName]['example']) ? $this->loadFile($manifest['services'][$serviceName]['example'], "$serviceName-example") : ['examples' => []];
            if (\count($serviceNames) > 1) {
                $operationNames = $this->getOperationNames(null, true, $io, $definitionArray, $waiterArray, $manifest['services'][$serviceName]);
            } else {
                $operationNames = $this->getOperationNames($input->getArgument('operation'), $input->getOption('all'), $io, $definitionArray, $waiterArray, $manifest['services'][$serviceName]);
            }
            if (\is_int($operationNames)) {
                return $operationNames;
            }

            $progressOperation->start(\count($operationNames));

            $definition = new ServiceDefinition($serviceName, $definitionArray, $documentationArray, $paginationArray, $waiterArray, $exampleArray);
            $serviceGenerator = $this->generator->service($manifest['services'][$serviceName]['namespace'] ?? \sprintf('AsyncAws\\%s', $serviceName));

            $clientClass = $serviceGenerator->client()->generate($definition);

            foreach ($operationNames as $operationName) {
                $progressOperation->setMessage($operationName);
                $progressOperation->advance();
                $progressOperation->display();

                if (null !== $operation = $definition->getOperation($operationName)) {
                    $serviceGenerator->operation()->generate($operation);
                } elseif (null !== $waiter = $definition->getWaiter($operationName)) {
                    $serviceGenerator->waiter()->generate($waiter);
                } else {
                    $io->error(\sprintf('Could not find service or waiter named "%s".', $operationName));

                    return 1;
                }

                // Update manifest file
                if (!\in_array($operationName, $manifest['services'][$serviceName]['methods'])) {
                    $manifest['services'][$serviceName]['methods'][] = $operationName;
                    sort($manifest['services'][$serviceName]['methods']);
                }

                ++$operationCounter;
            }

            if (!$input->getOption('raw')) {
                $progressOperation->setMessage('Fixing CS');
                $progressOperation->display();
                $this->fixCS($clientClass, $io);
            }

            $progressOperation->finish();
        }

        $this->dumpManifest($manifest);

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
    private function getOperationNames(?string $inputOperationName, bool $returnAll, SymfonyStyle $io, array $definition, array $waiter, array $manifest)
    {
        if ($inputOperationName) {
            if ($returnAll) {
                $io->error(\sprintf('Cannot use "--all" together with an operation. You passed "%s" as operation.', $inputOperationName));

                return 1;
            }

            if (!isset($definition['operations'][$inputOperationName]) && !isset($waiter['waiters'][$inputOperationName])) {
                $io->error(\sprintf('Could not find operation or waiter named "%s".', $inputOperationName));

                return 1;
            }

            if (!\in_array($inputOperationName, $manifest['methods'])) {
                $io->warning(\sprintf('Operation named "%s" has never been generated.', $inputOperationName));
                if (!$io->confirm('Do you want adding it?', true)) {
                    return 1;
                }
            }

            return [$inputOperationName];
        }

        $operations = $manifest['methods'];
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
            $choices = \array_values(array_diff(\array_keys($definition['operations'] + $waiter['waiters']), $manifest['methods']));
            $question = new ChoiceQuestion('Select the operation(s) to generate', $choices);
            $question->setMultiselect(true);

            return $io->askQuestion($question);
        }

        return [$operationName];
    }

    private function fixCs(ClassName $clientClass, SymfonyStyle $io): void
    {
        $reflection = new \ReflectionClass($clientClass->getFqdn());
        $path = \dirname($reflection->getFileName());

        // assert this
        $baseDir = \dirname($this->manifestFile);
        if (!\file_exists($baseDir . '/.php_cs')) {
            $io->warning('Unable to run php-cs-fixer. Please define a .php_cs file alongside the manifest.json file');

            return;
        }

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

        $e = new ErrorsManager();
        $runner = new Runner(
            $resolver->getFinder(),
            $resolver->getFixers(),
            $resolver->getDiffer(),
            null,
            $e,
            $resolver->getLinter(),
            $resolver->isDryRun(),
            $resolver->getCacheManager(),
            $resolver->getDirectory(),
            $resolver->shouldStopOnViolation()
        );
        $runner->fix();
        foreach ($e->getInvalidErrors() as $error) {
            $io->error(sprintf('The generated file "%s" is invalid: %s', $error->getFilePath(), $error->getSource() ? $error->getSource()->getMessage() : 'unknown'));
        }
    }

    private function loadFile(string $path, string $cacheKey): array
    {
        $path = \strtr($path, $this->loadManifest()['variables'] ?? [[]]);

        if (null === $this->cache) {
            $this->cache = \file_exists($this->cacheFile) ? require($this->cacheFile) : [];

            // temporary => to remove old way to cache files
            foreach ($this->cache as $key => $data) {
                if (0 === \strpos($key, 'https://')) {
                    unset($this->cache[$key]);
                }
            }
        }

        if (isset($this->cache[$cacheKey])) {
            if ($path !== ($this->cache[$cacheKey]['path'] ?? null)) {
                unset($this->cache[$cacheKey]);
            }
        }

        if (!isset($this->cache[$cacheKey])) {
            $this->cache[$cacheKey] = [
                'path' => $path,
                'content' => \json_decode(\file_get_contents($path), true),
            ];

            \file_put_contents($this->cacheFile, '<?php return ' . \var_export($this->cache, true) . ';');
        }

        return $this->cache[$cacheKey]['content'];
    }

    private function loadManifest(): array
    {
        if (null !== $this->manifest) {
            return $this->manifest;
        }

        $this->manifest = \json_decode(\file_get_contents($this->manifestFile), true);

        return $this->manifest;
    }

    private function dumpManifest(array $manifest): void
    {
        $this->manifest = $manifest;
        \file_put_contents($this->manifestFile, \json_encode($this->manifest, \JSON_PRETTY_PRINT));
    }
}
