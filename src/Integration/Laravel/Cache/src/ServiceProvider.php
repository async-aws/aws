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
            /** @phpstan-ignore-next-line */
            $config['prefix'] = $this->getPrefix($config);

            /** @phpstan-ignore-next-line */
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

            if (!empty($config['endpoint'])) {
                $clientConfig['endpoint'] = $config['endpoint'];
            }

            if (!empty($config['region'])) {
                $clientConfig['region'] = $config['region'];
            }

            $sesClient = new DynamoDbClient($clientConfig);
            $store = new AsyncAwsDynamoDbStore(
                $sesClient, $config['table'],
                $config['attributes']['key'] ?? 'key',
                $config['attributes']['value'] ?? 'value',
                $config['attributes']['expiration'] ?? 'expires_at',
                $config['prefix'] ?? ''
            );

            return $store;
        });
    }
}
