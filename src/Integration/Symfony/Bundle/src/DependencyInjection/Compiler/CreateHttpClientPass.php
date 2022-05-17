<?php

namespace AsyncAws\Symfony\Bundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CreateHttpClientPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('http_client')) {
            $container->register('async_aws.http_client', HttpClientInterface::class)
                ->setFactory([HttpClient::class, 'create']);
            return;
        }

        $container->setAlias('async_aws.http_client', 'http_client');
    }
}
