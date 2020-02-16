<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AsyncAwsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $usedServices = $this->registerConfiguredServices($container, $config);
        $usedServices = $this->registerInstalledServices($container, $config, $usedServices);
        $this->autowireServices($container, $usedServices);
    }

    private function registerConfiguredServices(ContainerBuilder $container, array $config): array
    {
        $availableServices = AwsPackagesProvider::getAllServices();
        $usedServices = [];
        $defaultConfig = $config;
        unset($defaultConfig['services']);

        foreach ($config['services'] as $name => $data) {
            $client = $availableServices[$data['type']]['client'];
            if (!class_exists($client)) {
                throw new InvalidConfigurationException(sprintf('You have configured "async_aws.%s" but the "%s" package is not installed. Try running "composer require %s"', $name, $name, $availableServices[$name]['package']));
            }

            $config = array_merge($defaultConfig, $data);
            if ($config['register_service']) {
                $usedServices[$name] = $client;
                $this->addServiceDefinition($container, $name, $config, $client);
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

        unset($config['services']);
        $availableServices = AwsPackagesProvider::getAllServices();
        foreach ($availableServices as $name => $data) {
            if (\array_key_exists($name, $usedServices)) {
                continue;
            }

            $client = $data['client'];
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

        $definition = new Definition($clientClass);
        $definition->addArgument($config['config']);
        $definition->addArgument(isset($config['credential_provider']) ? new Reference($config['credential_provider']) : null);
        $definition->addArgument(isset($config['http_client']) ? new Reference($config['http_client']) : null);
        $definition->addArgument($logger);
        $container->setDefinition(sprintf('async_aws.service.%s', $name), $definition);
    }

    private function autowireServices(ContainerBuilder $container, array $usedServices): void
    {
        $awsServices = AwsPackagesProvider::getAllServices();
        foreach ($usedServices as $name => $client) {
            if (null === $client) {
                // This client is disabled.
                continue;
            }

            $serviceId = sprintf('async_aws.service.%s', $name);
            if (isset($awsServices[$name])) {
                $container->setAlias($client, $serviceId);

                continue;
            }

            // Assert: Custom name
            $container->registerAliasForArgument($serviceId, $client, $name);
        }
    }
}
