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

        $closure = $this->getClosure();
        $manager->extend('async-aws-dynamodb', function ($app, array $config) use ($closure) {
            return $this->repository($closure($app, $config));
        });
    }

    public function createStore($app, array $config): AsyncAwsDynamoDbStore
    {
        $closure = $this->getClosure();

        return $closure($app, $config);
    }

    private function getClosure(): \Closure
    {
        return \Closure::fromCallable(function ($app, array $config) {
            $clientConfig = [];
            if (isset($config['key']) && isset($config['secret'])) {
                $clientConfig['accessKeyId'] = $config['key'] ?? null;
                $clientConfig['accessKeySecret'] = $config['secret'] ?? null;
                $clientConfig['sessionToken'] = $config['token'] ?? null;
            }

            $sesClient = new DynamoDbClient($clientConfig);
            $store = new AsyncAwsDynamoDbStore($sesClient, $config['table']);

            return $store;
        });
    }
}
