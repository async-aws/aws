<?php

namespace AsyncAws\ElastiCache\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\ElastiCache\Input\DescribeCacheClustersMessage;

class DescribeCacheClustersMessageTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new DescribeCacheClustersMessage([
            'CacheClusterId' => 'change me',
            'MaxRecords' => 1337,
            'Marker' => 'change me',
            'ShowCacheNodeInfo' => false,
            'ShowCacheClustersNotInReplicationGroups' => false,
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            CacheClusterId=my-mem-cluster
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
