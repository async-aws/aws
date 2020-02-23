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
            ->fixXmlConfig('client')
            ->children()
                ->booleanNode('register_service')->info('If set to false, no services will be created.')->defaultTrue()->end()
                ->scalarNode('credential_provider')->info('A service name for AsyncAws\Core\Credentials\CredentialProvider.')->defaultNull()->end()
                ->scalarNode('http_client')->info('A service name for Symfony\Contracts\HttpClient\HttpClientInterface.')->defaultNull()->end()
                ->scalarNode('logger')->info('A service name for Psr\Log\LoggerInterface.')->end()
                ->arrayNode('config')->info('Default config that will be merged will all services.')->normalizeKeys(false)->prototype('variable')->end()->end()

                ->arrayNode('clients')
                    ->beforeNormalization()->always()->then(\Closure::fromCallable([$this, 'validateType']))->end()
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->booleanNode('register_service')->info('If set to false, no service will be created for this AWS type.')->defaultTrue()->end()
                            ->arrayNode('config')->info('Configuration specific to this service.')->normalizeKeys(false)->prototype('variable')->end()->end()
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

    private static function validateType(?array $clients)
    {
        if (null === $clients) {
            return [];
        }

        $awsServices = AwsPackagesProvider::getServiceNames();
        foreach ($clients as $name => $config) {
            if (\in_array($name, $awsServices)) {
                if (isset($config['type']) && $name !== $config['type']) {
                    throw new InvalidConfigurationException(sprintf('You cannot define a service named "%s" with type "%s". That is super confusing.', $name, $config['type']));
                }
                $clients[$name]['type'] = $name;
            } elseif (!isset($config['type'])) {
                if (!\in_array($name, $awsServices)) {
                    throw new InvalidConfigurationException(sprintf('The "async_aws.client.%s" does not have a type. We were unable to guess what AWS service you want. Please add "aws.service.%s.type".', $name, $name));
                }

                $clients[$name]['type'] = $name;
            }
        }

        return $clients;
    }
}
