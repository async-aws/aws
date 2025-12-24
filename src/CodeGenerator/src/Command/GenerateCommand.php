<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Command;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\File\Cache;
use AsyncAws\CodeGenerator\File\ClassWriter;
use AsyncAws\CodeGenerator\File\ComposerWriter;
use AsyncAws\CodeGenerator\File\UnusedClassCleaner;
use AsyncAws\CodeGenerator\Generator\ApiGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use PhpCsFixer\Config;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Error\ErrorsManager;
use PhpCsFixer\Runner\Runner;
use PhpCsFixer\ToolInfo;
use Swaggest\JsonDiff\JsonPatch;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Update an existing response class or API client method.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
#[AsCommand(name: 'generate', description: 'Create or update API client methods.', aliases: ['update'])]
class GenerateCommand extends Command
{
    /**
     * @var array|null
     */
    private $manifest;

    /**
     * @var string
     */
    private $manifestFile;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var ApiGenerator
     */
    private $generator;

    /**
     * @var ClassWriter
     */
    private $classWriter;

    /**
     * @var ComposerWriter
     */
    private $composerWriter;

    /**
     * @var UnusedClassCleaner
     */
    private $unusedClassCleaner;

    public function __construct(string $manifestFile, Cache $cache, ClassWriter $classWriter, ComposerWriter $composerWriter, ApiGenerator $generator, UnusedClassCleaner $unusedClassCleaner)
    {
        $this->manifestFile = $manifestFile;
        $this->cache = $cache;
        $this->generator = $generator;
        $this->classWriter = $classWriter;
        $this->composerWriter = $composerWriter;
        $this->unusedClassCleaner = $unusedClassCleaner;

        parent::__construct('generate');
    }

    protected function configure(): void
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var ConsoleOutputInterface $output */
        $io = new SymfonyStyle($input, $output);

        /** @var ConsoleOutputInterface $output */
        $manifest = $this->loadManifest();
        $endpoints = $this->loadFile($manifest['endpoints'], 'endpoints');
        $serviceNames = $this->getServiceNames($input->getArgument('service'), $input->getOption('all'), $io, $manifest['services']);
        if (\is_int($serviceNames)) {
            return $serviceNames;
        }

        if (\count($serviceNames) > 1 && $input->getOption('all') && \extension_loaded('pcntl')) {
            $manifest = $this->generateServicesParallel($io, $input, $output, $manifest, $endpoints, $serviceNames);
        } else {
            $manifest = $this->generateServicesSequential($io, $input, $output, $manifest, $endpoints, $serviceNames);
        }

        if (\is_int($manifest)) {
            return $manifest;
        }

        $this->dumpManifest($manifest);
        $io->success('Operations generated');

        return 0;
    }

    /**
     * @return array|int
     */
    private function generateServicesParallel(SymfonyStyle $io, InputInterface $input, ConsoleOutputInterface $output, array $manifest, array $endpoints, array $serviceNames)
    {
        $progress = (new SymfonyStyle($input, $output->section()))->createProgressBar();
        $progress->setFormat(' [%bar%] %message%');
        $progress->setMessage('...');
        $progress->start(\count($serviceNames));

        $pids = [];
        foreach ($serviceNames as $serviceName) {
            $pid = pcntl_fork();
            if (-1 == $pid) {
                throw new \RuntimeException('Failed to fork');
            }
            if (!$pid) {
                $code = $this->generateService($io, $input, $manifest, $endpoints, $serviceName);
                if (\is_int($code)) {
                    exit($code);
                }
                exit(0);
            }

            $pids[$pid] = $serviceName;
        }

        while (\count($pids) > 0) {
            $pid = pcntl_wait($status);
            if (0 !== $status) {
                return $status;
            }

            $progress->setMessage($pids[$pid]);
            $progress->advance();
            $progress->display();
            unset($pids[$pid]);
        }
        $progress->finish();

        return $manifest;
    }

    /**
     * @param string[] $serviceNames
     *
     * @return array|int
     */
    private function generateServicesSequential(SymfonyStyle $io, InputInterface $input, ConsoleOutputInterface $output, array $manifest, array $endpoints, array $serviceNames)
    {
        if (\count($serviceNames) > 1) {
            $progress = (new SymfonyStyle($input, $output->section()))->createProgressBar();
            $progress->setFormat(' [%bar%] %message%');
            $progress->setMessage('...');
            $progress->start(\count($serviceNames));
        }

        foreach ($serviceNames as $serviceName) {
            $manifest = $this->generateService($io, $input, $manifest, $endpoints, $serviceName);
            if (\is_int($manifest)) {
                return $manifest;
            }

            if (isset($progress)) {
                $progress->setMessage($serviceName);
                $progress->advance();
                $progress->display();
            }
        }

        if (isset($progress)) {
            $progress->finish();
        }

        return $manifest;
    }

    private function extractEndpointsForService(array $endpoints, string $prefix, string $signingServiceFallback, string $signingVersionFallback): array
    {
        $serviceEndpoints = [];
        foreach ($endpoints['partitions'] as $partition) {
            $suffix = $partition['dnsSuffix'];
            $service = $partition['services'][$prefix] ?? [];
            foreach ($service['endpoints'] ?? [] as $region => $config) {
                $hostname = $config['hostname'] ?? $service['defaults']['hostname'] ?? $partition['defaults']['hostname'];
                $protocols = $config['protocols'] ?? $service['defaults']['protocols'] ?? $partition['defaults']['protocols'] ?? [];
                $signRegion = $config['credentialScope']['region'] ?? $service['defaults']['credentialScope']['region'] ?? $partition['defaults']['credentialScope']['region'] ?? $region;
                $signService = $config['credentialScope']['service'] ?? $service['defaults']['credentialScope']['service'] ?? $partition['defaults']['credentialScope']['service'] ?? $signingServiceFallback;
                $signVersions = array_unique($config['signatureVersions'] ?? $service['defaults']['signatureVersions'] ?? $partition['defaults']['signatureVersions'] ?? [$signingVersionFallback]);

                if (empty($config)) {
                    if (!isset($serviceEndpoints['_default'][$partition['partition']])) {
                        $endpoint = strtr(\sprintf('http%s://%s', \in_array('https', $protocols) ? 's' : '', $hostname), [
                            '{service}' => $prefix,
                            '{region}' => '%region%',
                            '{dnsSuffix}' => $suffix,
                        ]);
                        $serviceEndpoints['_default'][$partition['partition']] = [
                            'endpoint' => $endpoint,
                            'regions' => [$region],
                            'signService' => $signService,
                            'signVersions' => $signVersions,
                        ];
                    } else {
                        $serviceEndpoints['_default'][$partition['partition']]['regions'][] = $region;
                    }
                } else {
                    $endpoint = strtr(\sprintf('http%s://%s', \in_array('https', $protocols) ? 's' : '', $hostname), [
                        '{service}' => $prefix,
                        '{region}' => $region,
                        '{dnsSuffix}' => $suffix,
                    ]);

                    $serviceEndpoints[$region] = [
                        'endpoint' => $endpoint,
                        'signRegion' => $signRegion,
                        'signService' => $signService,
                        'signVersions' => $signVersions,
                    ];
                }
            }
            if (isset($service['partitionEndpoint'])) {
                if (!isset($serviceEndpoints[$service['partitionEndpoint']])) {
                    throw new \RuntimeException('Missing global region config');
                }
                $serviceEndpoints['_global'][$partition['partition']] = $serviceEndpoints[$service['partitionEndpoint']];
                unset($serviceEndpoints[$service['partitionEndpoint']]);
                unset($serviceEndpoints['_global'][$partition['partition']]['region']);
                $serviceEndpoints['_global'][$partition['partition']]['regions'] = [];
                if (!($service['isRegionalized'] ?? true)) {
                    foreach ($partition['regions'] as $region => $_) {
                        if (isset($serviceEndpoints[$region])) {
                            continue;
                        }
                        if (\in_array($region, $serviceEndpoints['_default'][$partition['partition']]['regions'] ?? [])) {
                            continue;
                        }
                        $serviceEndpoints['_global'][$partition['partition']]['regions'][] = $region;
                    }
                }
            }
        }

        if (empty($serviceEndpoints)) {
            foreach ($endpoints['partitions'] as $partition) {
                $serviceEndpoints['_default'][$partition['partition']] = [
                    'endpoint' => "https://$prefix.%region%.amazonaws.com",
                    'regions' => array_keys($partition['regions']),
                    'signService' => $partition['defaults']['credentialScope']['service'] ?? $signingServiceFallback,
                    'signVersions' => $partition['defaults']['signatureVersions'] ?? [$signingVersionFallback],
                ];
            }
        }

        return $serviceEndpoints;
    }

    /**
     * @return array|int
     */
    private function generateService(SymfonyStyle $io, InputInterface $input, array $manifest, array $endpoints, string $serviceName)
    {
        $definitionArray = $this->loadFile($manifest['services'][$serviceName]['source'], "$serviceName-source", $manifest['services'][$serviceName]['patches']['source'] ?? []);
        $endpoints = $this->extractEndpointsForService($endpoints, $definitionArray['metadata']['endpointPrefix'], $definitionArray['metadata']['signingName'] ?? $definitionArray['metadata']['endpointPrefix'], $definitionArray['metadata']['signatureVersion']);

        $documentationArray = $this->loadFile($manifest['services'][$serviceName]['documentation'], "$serviceName-documentation", $manifest['services'][$serviceName]['patches']['documentation'] ?? []);
        $paginationArray = $this->loadFile($manifest['services'][$serviceName]['pagination'], "$serviceName-pagination", $manifest['services'][$serviceName]['patches']['pagination'] ?? []);
        $waiterArray = isset($manifest['services'][$serviceName]['waiter']) ? $this->loadFile($manifest['services'][$serviceName]['waiter'], "$serviceName-waiter", $manifest['services'][$serviceName]['patches']['waiter'] ?? []) : ['waiters' => []];
        $exampleArray = isset($manifest['services'][$serviceName]['example']) ? $this->loadFile($manifest['services'][$serviceName]['example'], "$serviceName-example", $manifest['services'][$serviceName]['patches']['example'] ?? []) : ['examples' => []];
        $customErrorFactory = $manifest['services'][$serviceName]['error-factory'] ?? null;

        $operationNames = $this->getOperationNames($input->getArgument('operation'), $input->getOption('all'), $io, $definitionArray, $waiterArray, $manifest['services'][$serviceName]);
        if (\is_int($operationNames)) {
            return $operationNames;
        }

        $managedOperations = array_unique(array_merge($manifest['services'][$serviceName]['methods'], $operationNames));
        $definition = new ServiceDefinition($serviceName, $endpoints, $definitionArray, $documentationArray, $paginationArray, $waiterArray, $exampleArray, $manifest['services'][$serviceName]['hooks'] ?? [], $manifest['services'][$serviceName]['api-reference'] ?? null);
        $serviceGenerator = $this->generator->service($namespace = $manifest['services'][$serviceName]['namespace'] ?? \sprintf('AsyncAws\\%s', $serviceName), $managedOperations);

        $clientClass = $serviceGenerator->client()->generate($definition, $customErrorFactory);

        foreach ($operationNames as $operationName) {
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
        }

        foreach ($this->generator->getUpdatedClasses() as $class) {
            $this->classWriter->write($class);
        }

        $this->composerWriter->setRequirements($namespace, $this->generator->getUpdatedRequirements(), $input->getOption('all') && 'Sts' !== $serviceName);

        if ($input->getOption('all')) {
            $this->unusedClassCleaner->cleanUnusedClasses($serviceGenerator->getNamespaceRegistry(), $this->generator->getUpdatedClasses());
        }

        if (!$input->getOption('raw')) {
            $this->fixCs($clientClass, $io);
        }

        return $manifest;
    }

    /**
     * @param array<string, mixed> $manifest
     *
     * @return list<string>|int
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
        $serviceName = $io->choice('Select the service to generate', array_merge([$allServices], $services));
        if ($serviceName === $allServices) {
            return $services;
        }

        return [$serviceName];
    }

    /**
     * @param array{operations: array<string, mixed>,...} $definition
     * @param array{waiters: array<string, mixed>}        $waiter
     * @param array{methods: list<string>,...}            $manifest
     *
     * @return string[]|int
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

        $operationName = $io->choice('Select the operation to generate', array_merge([$allOperations, $newOperation], $operations));
        if ($operationName === $allOperations) {
            return $operations;
        }
        if ($operationName === $newOperation) {
            $choices = array_values(array_diff(array_keys($definition['operations'] + $waiter['waiters']), $manifest['methods']));
            $question = new ChoiceQuestion('Select the operation(s) to generate', $choices);
            $question->setMultiselect(true);

            return $io->askQuestion($question);
        }

        return [$operationName];
    }

    private function fixCs(ClassName $clientClass, SymfonyStyle $io): void
    {
        $srcPath = \dirname((new \ReflectionClass($clientClass->getFqdn()))->getFileName());
        $testPath = substr($srcPath, 0, strrpos($srcPath, '/src')) . '/tests';

        if (!is_dir($srcPath)) {
            throw new \InvalidArgumentException(\sprintf('The src dir "%s" does not exists', $srcPath));
        }
        if (!is_dir($testPath)) {
            throw new \InvalidArgumentException(\sprintf('The test dir "%s" does not exists', $testPath));
        }

        // assert this
        $baseDir = \dirname($this->manifestFile);
        if (!file_exists($baseDir . '/.php-cs-fixer.php')) {
            $io->warning('Unable to run php-cs-fixer. Please define a .php-cs-fixer.php file alongside the manifest.json file');

            return;
        }

        $resolver = new ConfigurationResolver(
            new Config(),
            [
                'config' => $baseDir . '/.php-cs-fixer.php',
                'allow-risky' => 'yes',
                'dry-run' => false,
                'path' => [$srcPath, $testPath],
                'path-mode' => 'override',
                'using-cache' => 'yes',
                'cache-file' => $baseDir . '/.cache/php-cs-fixer/.generate-' . $clientClass->getName() . '.cache',
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
            $io->error(\sprintf('The generated file "%s" is invalid: %s', $error->getFilePath(), $error->getSource() ? $error->getSource()->getMessage() : 'unknown'));
        }
        if (empty($e->getInvalidErrors())) {
            $runner->fix();
        }
    }

    private function loadFile(string $path, string $cacheKey, array $patch = []): array
    {
        $path = strtr($path, $this->loadManifest()['variables'] ?? []);

        // For files loaded from a URL, we want to cache them until the URL changes.
        $needsCaching = false !== strpos($path, '://');

        $data = $needsCaching ? $this->cache->get(__CLASS__ . ':' . $cacheKey) : null;
        if (null === $data || $path !== ($data['path'] ?? null) || $patch !== ($data['patch'] ?? [])) {
            if (empty($patch)) {
                $content = json_decode(file_get_contents($path), true);
            } else {
                // use a non associative object to apply patch
                $content = json_decode(file_get_contents($path), false);
                $jsonPatch = JsonPatch::import($patch);
                $jsonPatch->apply($content);
                // convert to associative array
                $content = json_decode(json_encode($content), true);
            }

            $data = [
                'path' => $path,
                'content' => $content,
                'patch' => $patch,
            ];

            if ($needsCaching) {
                $this->cache->set(__CLASS__ . ':' . $cacheKey, $data);
            }
        }

        return $data['content'];
    }

    private function loadManifest(): array
    {
        if (null !== $this->manifest) {
            return $this->manifest;
        }

        $this->manifest = json_decode(file_get_contents($this->manifestFile), true);

        return $this->manifest;
    }

    private function dumpManifest(array $manifest): void
    {
        $this->manifest = $manifest;
        file_put_contents($this->manifestFile, json_encode($this->manifest, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES) . \PHP_EOL);
    }
}
