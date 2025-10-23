<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Cache\Tests\Unit;

use AsyncAws\Illuminate\Cache\AsyncAwsDynamoDbStore;
use AsyncAws\Illuminate\Cache\ServiceProvider;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testCreateStore()
    {
        $app = 'app';
        $serviceProvider = new ServiceProvider($app);
        $store = $serviceProvider->createStore($app, [
            'key' => 'my_key',
            'secret' => 'my_secret',
            'table' => 'cache-table',
        ]);

        self::assertInstanceOf(AsyncAwsDynamoDbStore::class, $store);
        $refl = new \ReflectionClass($store);
        $property = $refl->getProperty('dynamoDb');
        if (\PHP_VERSION_ID < 80100) {
            $property->setAccessible(true);
        }
        $client = $property->getValue($store);

        $config = $client->getConfiguration();
        self::assertEquals('my_key', $config->get('accessKeyId'));
        self::assertEquals('my_secret', $config->get('accessKeySecret'));
    }

    public function testCreateStoreWitMinimalConfig()
    {
        $app = 'app';
        $serviceProvider = new ServiceProvider($app);
        $store = $serviceProvider->createStore($app, [
            'table' => 'cache-table',
        ]);

        self::assertInstanceOf(AsyncAwsDynamoDbStore::class, $store);
    }
}
