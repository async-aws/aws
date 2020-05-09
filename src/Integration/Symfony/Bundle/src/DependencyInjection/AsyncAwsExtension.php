<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle\DependencyInjection;

use AsyncAws\Symfony\Bundle\Secrets\CachedEnvVarLoader;
use AsyncAws\Symfony\Bundle\Secrets\SsmVault;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Contracts\Cache\CacheInterface;

class AsyncAwsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $usedServices = $this->registerConfiguredServices($container, $config);
        $usedServices = $this->registerInstalledServices($container, $config, $usedServices);
        $this->registerEnvLoader($container, $config);
        $this->autowireServices($container, $usedServices);
    }

    private function registerConfiguredServices(ContainerBuilder $container, array $config): array
    {
        $availableServices = AwsPackagesProvider::getAllServices();
        $usedServices = [];
        $defaultConfig = $config;
        unset($defaultConfig['clients'], $defaultConfig['secrets']);

        foreach ($config['clients'] as $name => $data) {
            $client = $availableServices[$data['type']]['class'];
            if (!class_exists($client)) {
                throw new InvalidConfigurationException(sprintf('You have configured "async_aws.%s" but the "%s" package is not installed. Try running "composer require %s"', $name, $data['type'], $availableServices[$data['type']]['package']));
            }

            $serviceConfig = array_merge($defaultConfig, $data);
            $serviceConfig['config'] = array_merge($defaultConfig['config'], $data['config']);
            if ($serviceConfig['register_service']) {
                $usedServices[$name] = $client;
                $this->addServiceDefinition($container, $name, $serviceConfig, $client);
            } else {
                $usedServices[$name] = null;
            }
        }

        return $usedServices;
    }

    private function registerInstalledServices(ContainerBuilder $container, array $config, array $usedServices): array
    {
        if (!$config['register_service']) {
            return $usedServices;
        }

        unset($config['clients']);
        $availableServices = AwsPackagesProvider::getAllServices();
        foreach ($availableServices as $name => $data) {
            if (\array_key_exists($name, $usedServices)) {
                continue;
            }

            $client = $data['class'];
            if (!class_exists($client)) {
                continue;
            }

            $usedServices[$name] = $client;
            $this->addServiceDefinition($container, $name, $config, $client);
        }

        return $usedServices;
    }

    private function addServiceDefinition(ContainerBuilder $container, string $name, array $config, string $clientClass): void
    {
        if (\array_key_exists('logger', $config)) {
            $logger = $config['logger'] ? new Reference($config['logger']) : null;
        } else {
            // Use default Symfony logger unless explicitly set to null.
            $logger = new Reference('logger', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        }
        if (\array_key_exists('http_client', $config)) {
            $httpClient = $config['http_client'] ? new Reference($config['http_client']) : null;
        } else {
            // Use default Symfony http_client unless explicitly set to null.
            $httpClient = new Reference('http_client', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        }

        $definition = new Definition($clientClass);
        $definition->addArgument($config['config']);
        $definition->addArgument(isset($config['credential_provider']) ? new Reference($config['credential_provider']) : null);
        $definition->addArgument($httpClient);
        $definition->addArgument($logger);
        $container->setDefinition(sprintf('async_aws.client.%s', $name), $definition);
    }

    private function registerEnvLoader(ContainerBuilder $container, array $config): void
    {
        if (!$config['secrets']['enabled']) {
            return;
        }

        $availableServices = AwsPackagesProvider::getAllServices();
        if (!class_exists($className = $availableServices['ssm']['class'])) {
            throw new InvalidConfigurationException(sprintf('You have enabled "async_aws.secrets" but the "%s" package is not installed. Try running "composer require %s"', 'ssm', $availableServices['ssm']['package']));
        }

        if (null !== $client = $config['secrets']['client']) {
            if (!isset($config['clients'][$client])) {
                throw new InvalidConfigurationException(sprintf('The client "%s" configured in "async_aws.secrets" does not exists. Available clients are "%s"', $client, implode(', ', \array_keys($config['clients']))));
            }
            if ('ssm' !== $config['clients'][$client]['type']) {
                throw new InvalidConfigurationException(sprintf('The client "%s" configured in "async_aws.secrets" is not a SSM client.', $client));
            }
        } else {
            if (!isset($config['clients']['ssm'])) {
                $client = 'ssm';
            } else {
                $client = 'secrets';
                $i = 1;
                while (isset($config['clients'][$client])) {
                    $client = 'secrets_' . $i;
                }
            }
            $this->addServiceDefinition($container, $client, $config, $className);
        }

        $container->register(SsmVault::class)
            ->setAutoconfigured(true)
            ->setArguments([
                new Reference('async_aws.client.' . $client),
                $config['secrets']['path'],
                $config['secrets']['recursive'],
            ]);

        if ($config['secrets']['cache']['enabled']) {
            if (!\interface_exists(CacheInterface::class)) {
                throw new InvalidConfigurationException(sprintf('You have enabled "async_aws.secrets.cache" but the "symfony/cache" package is not installed. Try running "composer require symfony/cache"'));
            }

            $container->Register(CachedEnvVarLoader::class)
                ->setDecoratedService(SsmVault::class)
                ->setArguments([
                    new Reference(CachedEnvVarLoader::class . '.inner'),
                    new Reference($config['secrets']['cache']['pool']),
                    $config['secrets']['cache']['ttl'],
                ]);
        }
    }

    private function autowireServices(ContainerBuilder $container, array $usedServices): void
    {
        $awsServices = AwsPackagesProvider::getAllServices();
        foreach ($usedServices as $name => $client) {
            if (null === $client) {
                // This client is disabled.
                continue;
            }

            $serviceId = sprintf('async_aws.client.%s', $name);
            if (isset($awsServices[$name])) {
                $container->setAlias($client, $serviceId);

                continue;
            }

            // Assert: Custom name
            $container->registerAliasForArgument($serviceId, $client, $name);
        }
    }
}
