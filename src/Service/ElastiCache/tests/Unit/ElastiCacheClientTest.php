<?php

namespace AsyncAws\ElastiCache\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ElastiCache\ElastiCacheClient;
use AsyncAws\ElastiCache\Input\DescribeCacheClustersMessage;
use AsyncAws\ElastiCache\Result\CacheClusterMessage;
use Symfony\Component\HttpClient\MockHttpClient;

class ElastiCacheClientTest extends TestCase
{
    public function testDescribeCacheClusters(): void
    {
        $client = new ElastiCacheClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeCacheClustersMessage([

        ]);
        $result = $client->describeCacheClusters($input);

        self::assertInstanceOf(CacheClusterMessage::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
