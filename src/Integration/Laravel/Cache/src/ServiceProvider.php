<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Cache;

use AsyncAws\DynamoDb\DynamoDbClient;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\ServiceProvider as AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider
{
    public function boot()
    {
        /** @var CacheManager $manager */
        $manager = $this->app['cache'];

        $manager->extend('async-aws-dynamodb', \Closure::fromCallable([$this, 'createStore']));
    }

    public function createStore($app, array $config): AsyncAwsDynamoDbStore
    {
        $clientConfig = [];
        if (isset($config['key']) && isset($config['secret'])) {
            $clientConfig['accessKeyId'] = $config['key'] ?? null;
            $clientConfig['accessKeySecret'] = $config['secret'] ?? null;
            $clientConfig['sessionToken'] = $config['token'] ?? null;
        }

        $sesClient = new DynamoDbClient($clientConfig);

        return new AsyncAwsDynamoDbStore($sesClient, $config['table']);
    }
}
