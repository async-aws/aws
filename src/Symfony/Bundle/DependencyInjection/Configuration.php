<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('async_aws');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->fixXmlConfig('service')
            ->children()
                ->booleanNode('register_service')->info('If set to false, no service will be created.')->defaultTrue()->end()
                ->scalarNode('credential_provider')->info('A service name for AsyncAws\Core\Credentials\CredentialProvider.')->defaultNull()->end()
                ->scalarNode('http_client')->info('A service name for Symfony\Contracts\HttpClient\HttpClientInterface.')->defaultNull()->end()
                ->scalarNode('logger')->info('A service name for Psr\Log\LoggerInterface.')->end()
                ->arrayNode('config')->normalizeKeys(false)->prototype('variable')->end()->end()

                ->arrayNode('services')
                    ->beforeNormalization()->always()->then(\Closure::fromCallable([$this, 'validateType']))->end()
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->booleanNode('register_service')->info('If set to false, no service will be created.')->defaultTrue()->end()
                            ->arrayNode('config')->normalizeKeys(false)->prototype('variable')->end()->end()
                            ->enumNode('type')->info('A valid AWS type. The service name will be used as default. ')->values(AwsPackagesProvider::getServiceNames())->end()
                            ->scalarNode('credential_provider')->info('A service name for AsyncAws\Core\Credentials\CredentialProvider.')->end()
                            ->scalarNode('http_client')->info('A service name for Symfony\Contracts\HttpClient\HttpClientInterface.')->end()
                            ->scalarNode('logger')->info('A service name for Psr\Log\LoggerInterface.')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    private static function validateType(array $services)
    {
        $awsServices = AwsPackagesProvider::getServiceNames();
        foreach ($services as $name => $config) {
            if (\in_array($name, $awsServices)) {
                if (isset($config['type']) && $name !== $config['type']) {
                    throw new InvalidConfigurationException(sprintf('You cannot define a service named "%s" with type "%s". That is super confusing.', $name, $config['type']));
                }
                $services[$name]['type'] = $name;
            } elseif (!isset($config['type'])) {
                if (!\in_array($name, $awsServices)) {
                    throw new InvalidConfigurationException(sprintf('The "async_aws.service.%s" does not have a type. We were unable to guess what AWS service you want. Please add "aws.service.%s.type".', $name, $name));
                }

                $services[$name]['type'] = $name;
            }
        }

        return $services;
    }
}
