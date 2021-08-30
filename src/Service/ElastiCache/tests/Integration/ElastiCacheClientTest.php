<?php

namespace AsyncAws\ElastiCache\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ElastiCache\ElastiCacheClient;
use AsyncAws\ElastiCache\Input\DescribeCacheClustersMessage;

class ElastiCacheClientTest extends TestCase
{
    public function testDescribeCacheClusters(): void
    {
        $client = $this->getClient();

        $input = new DescribeCacheClustersMessage([
            'CacheClusterId' => 'change me',
            'MaxRecords' => 1337,
            'Marker' => 'change me',
            'ShowCacheNodeInfo' => false,
            'ShowCacheClustersNotInReplicationGroups' => false,
        ]);
        $result = $client->describeCacheClusters($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getMarker());
        // self::assertTODO(expected, $result->getCacheClusters());
    }

    private function getClient(): ElastiCacheClient
    {
        self::markTestSkipped('Localstack does not support ElastiCache in the free version');

        return new ElastiCacheClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
