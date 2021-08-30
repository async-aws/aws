<?php

namespace AsyncAws\ElastiCache\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\ElastiCache\Input\DescribeCacheClustersMessage;

class DescribeCacheClustersMessageTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeCacheClustersMessage([
            'CacheClusterId' => 'my-mem-cluster',
            'ShowCacheNodeInfo' => false,
            'ShowCacheClustersNotInReplicationGroups' => false,
        ]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DescribeCacheClusters&
            CacheClusterId=my-mem-cluster&
            ShowCacheClustersNotInReplicationGroups=false&
            ShowCacheNodeInfo=false&
            Version=2015-02-02
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
