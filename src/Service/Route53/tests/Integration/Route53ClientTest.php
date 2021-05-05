<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Enum\ChangeStatus;
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
            'Name' => 'test-domain.com',
            'CallerReference' => microtime(),
            'HostedZoneConfig' => new HostedZoneConfig([
                'Comment' => 'foo',
                'PrivateZone' => false,
            ]),
        ]);
        $result = $client->createHostedZone($input);

        $result->resolve();

        self::assertSame(ChangeStatus::PENDING, $result->getChangeInfo()->getStatus());
        self::assertSame('test-domain.com.', $result->getHostedZone()->getName());
        self::assertSame('foo', $result->getHostedZone()->getConfig()->getComment());
        self::assertFalse($result->getHostedZone()->getConfig()->getPrivateZone());
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

    public function testDeleteHostedZone(): void
    {
        $client = $this->getClient();

        $input = new DeleteHostedZoneRequest([
            'Id' => 'change me',
        ]);
        $result = $client->DeleteHostedZone($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getChangeInfo());
    }

    public function testListHostedZonesByName(): void
    {
        $client = $this->getClient();

        $input = new ListHostedZonesByNameRequest([
            'DNSName' => 'change me',
            'HostedZoneId' => 'change me',
            'MaxItems' => 'change me',
        ]);
        $result = $client->ListHostedZonesByName($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getHostedZones());
        self::assertSame('changeIt', $result->getDNSName());
        self::assertSame('changeIt', $result->getHostedZoneId());
        self::assertFalse($result->getIsTruncated());
        self::assertSame('changeIt', $result->getNextDNSName());
        self::assertSame('changeIt', $result->getNextHostedZoneId());
        self::assertSame('changeIt', $result->getMaxItems());
    }

    public function testListResourceRecordSets(): void
    {
        $client = $this->getClient();

        $input = new ListResourceRecordSetsRequest([
            'HostedZoneId' => 'change me',
            'StartRecordName' => 'change me',
            'StartRecordType' => 'change me',
            'StartRecordIdentifier' => 'change me',
            'MaxItems' => 'change me',
        ]);
        $result = $client->ListResourceRecordSets($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getResourceRecordSets());
        self::assertFalse($result->getIsTruncated());
        self::assertSame('changeIt', $result->getNextRecordName());
        self::assertSame('changeIt', $result->getNextRecordType());
        self::assertSame('changeIt', $result->getNextRecordIdentifier());
        self::assertSame('changeIt', $result->getMaxItems());
    }

    private function getClient(): Route53Client
    {
        return new Route53Client([
            'endpoint' => 'http://localhost:4576',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
