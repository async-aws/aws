<?php

namespace AsyncAws\Route53\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Input\CreateHostedZoneRequest;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
use AsyncAws\Route53\Route53Client;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;

class Route53ClientTest extends TestCase
{
    public function testCreateHostedZone(): void
    {
        $client = $this->getClient();

        $input = new CreateHostedZoneRequest([
            'Name' => 'example.com',
            'CallerReference' => 'myUniqueIdentifier',
            'HostedZoneConfig' => new HostedZoneConfig([
                'Comment' => 'This is my first hosted zone.',
                'PrivateZone' => false,
            ]),
        ]);
        $result = $client->CreateHostedZone($input);

        $result->resolve();

        self::assertSame('example.com.', $result->getHostedZone()->getName());
    }

    public function testListHostedZones(): void
    {
        $client = $this->getClient();

        $client->CreateHostedZone(['Name' => 'example.com', 'CallerReference' => 'myUniqueIdentifier']);

        $input = new ListHostedZonesRequest([]);
        $result = $client->ListHostedZones($input);

        $result->resolve();

        $zones = iterator_to_array($result->getHostedZones());
        self::assertGreaterThan(0, $zones);
        self::assertFalse($result->getIsTruncated());
    }

    private function getClient(): Route53Client
    {
        return new Route53Client([
            'endpoint' => 'http://localhost:4576',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
