<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\EndpointDiscovery;

use AsyncAws\Core\EndpointDiscovery\EndpointCache;
use AsyncAws\Core\EndpointDiscovery\EndpointInterface;
use PHPUnit\Framework\TestCase;

class EndpointCacheTest extends TestCase
{
    public function testAddEndpoints()
    {
        $cache = new EndpointCache();

        $cache->addEndpoints('r1', [$this->getEndpoint('foo.com', -10)]);
        self::assertNull($cache->getActiveEndpoint('r1'));
        self::assertSame('https://foo.com', $cache->getExpiredEndpoint('r1'));

        $cache->addEndpoints('r1', [$this->getEndpoint('bar.com', -1), $this->getEndpoint('bar.com', -2)]);
        self::assertNull($cache->getActiveEndpoint('r1'));
        self::assertSame('https://bar.com', $cache->getExpiredEndpoint('r1'));

        $cache->addEndpoints('r2', [$this->getEndpoint('qux.com', 10)]);
        self::assertNull($cache->getActiveEndpoint('r1'));
        self::assertSame('https://qux.com', $cache->getActiveEndpoint('r2'));

        $cache->addEndpoints('r2', [$this->getEndpoint('foofoo.com', 20), $this->getEndpoint('barfoo.com', 15)]);
        self::assertSame('https://foofoo.com', $cache->getActiveEndpoint('r2'));

        $cache->removeEndpoint('https://foofoo.com');
        self::assertSame('https://barfoo.com', $cache->getActiveEndpoint('r2'));
    }

    private function getEndpoint(string $address, int $ttl): EndpointInterface
    {
        return new class($address, $ttl) implements EndpointInterface {
            private $address;

            private $ttl;

            public function __construct(string $address, int $ttl)
            {
                $this->address = $address;
                $this->ttl = $ttl;
            }

            public function getAddress(): string
            {
                return $this->address;
            }

            public function getCachePeriodInMinutes(): int
            {
                return $this->ttl;
            }
        };
    }
}
