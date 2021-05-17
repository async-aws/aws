<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Enum\ChangeAction;
use AsyncAws\Route53\Enum\RRType;
use AsyncAws\Route53\Input\ChangeResourceRecordSetsRequest;
use AsyncAws\Route53\Input\CreateHostedZoneRequest;
use AsyncAws\Route53\Input\DeleteHostedZoneRequest;
use AsyncAws\Route53\Input\ListHostedZonesByNameRequest;
use AsyncAws\Route53\Input\ListResourceRecordSetsRequest;
use AsyncAws\Route53\Route53Client;
use AsyncAws\Route53\ValueObject\Change;
use AsyncAws\Route53\ValueObject\ChangeBatch;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\ResourceRecord;
use AsyncAws\Route53\ValueObject\ResourceRecordSet;

class Route53ClientTest extends TestCase
{
    public function testChangeResourceRecordSets(): void
    {
        self::markTestSkipped('');

        $client = $this->getClient();

        $input = new ChangeResourceRecordSetsRequest([
            'HostedZoneId' => 'change me',
            'ChangeBatch' => new ChangeBatch([
                'Comment' => 'change me',
                'Changes' => [new Change([
                    'Action' => 'change me',
                    'ResourceRecordSet' => new ResourceRecordSet([
                        'Name' => 'change me',
                        'Type' => 'change me',
                        'SetIdentifier' => 'change me',
                        'Weight' => 1337,
                        'Region' => 'change me',
                        'GeoLocation' => new GeoLocation([
                            'ContinentCode' => 'change me',
                            'CountryCode' => 'change me',
                            'SubdivisionCode' => 'change me',
                        ]),
                        'Failover' => 'change me',
                        'MultiValueAnswer' => false,
                        'TTL' => 1337,
                        'ResourceRecords' => [new ResourceRecord([
                            'Value' => 'change me',
                        ])],
                        'AliasTarget' => new AliasTarget([
                            'HostedZoneId' => 'change me',
                            'DNSName' => 'change me',
                            'EvaluateTargetHealth' => false,
                        ]),
                        'HealthCheckId' => 'change me',
                        'TrafficPolicyInstanceId' => 'change me',
                    ]),
                ])],
            ]),
        ]);
        $result = $client->ChangeResourceRecordSets($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getChangeInfo());
    }

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

    public function testDeleteHostedZone(): void
    {
        $client = $this->getClient();

        $result = $client->CreateHostedZone([
            'Name' => 'example.com',
            'CallerReference' => 'myUniqueIdentifier',
        ]);

        $result->resolve();

        $input = new DeleteHostedZoneRequest([
            'Id' => ltrim($result->getHostedZone()->getId(), '/hostedzone/'),
        ]);
        $result = $client->DeleteHostedZone($input);

        $result->resolve();

        self::assertSame('PENDING', $result->getChangeInfo()->getStatus());
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

        $input = new CreateHostedZoneRequest([
            'Name' => 'foo-domain.com',
            'CallerReference' => microtime(),
        ]);
        $result = $client->createHostedZone($input);

        $result->resolve();

        $input = new DeleteHostedZoneRequest([
            'Id' => $result->getHostedZone()->getId(),
        ]);
        $result = $client->deleteHostedZone($input);

        $result->resolve();

        self::assertSame('PENDING', $result->getChangeInfo()->getStatus());
        self::assertNull($result->getChangeInfo()->getComment());
    }

    public function testListHostedZonesByName(): void
    {
        $client = $this->getClient();

        $input = new CreateHostedZoneRequest([
            'Name' => 'baz-domain.com',
            'CallerReference' => microtime(),
        ]);
        $result = $client->createHostedZone($input);

        $result->resolve();

        $input = new ListHostedZonesByNameRequest([
            'DNSName' => 'baz-domain.com',
            'MaxItems' => '1',
        ]);
        $result = $client->listHostedZonesByName($input);

        $result->resolve();

        self::assertCount(1, $result->getHostedZones());
        self::assertFalse($result->getIsTruncated());
    }

    public function testListResourceRecordSets(): void
    {
        $client = $this->getClient();

        $input = new CreateHostedZoneRequest([
            'Name' => 'bar-domain.com',
            'CallerReference' => microtime(),
        ]);
        $result = $client->createHostedZone($input);

        $result->resolve();

        $hostedZoneId = $result->getHostedZone()->getId();

        $input = new ChangeResourceRecordSetsRequest([
            'HostedZoneId' => $hostedZoneId,
            'ChangeBatch' => new ChangeBatch([
                'Changes' => [
                    new Change([
                        'Action' => ChangeAction::CREATE,
                        'ResourceRecordSet' => new ResourceRecordSet([
                            'SetIdentifier' => 'Main',
                            'Name' => 'bar-domain.com',
                            'Type' => RRType::A,
                            'TTL' => 300,
                            'ResourceRecords' => [
                                new ResourceRecord([
                                    'Value' => '34.145.17.120',
                                ]),
                            ],
                        ]),
                    ]),
                    new Change([
                        'Action' => ChangeAction::CREATE,
                        'ResourceRecordSet' => new ResourceRecordSet([
                            'SetIdentifier' => 'Main',
                            'Name' => 'bar-domain.com',
                            'Type' => RRType::SOA,
                            'TTL' => 60,
                            'ResourceRecords' => [
                                new ResourceRecord([
                                    'Value' => 'ns-1780.awsdns-30.co.uk. awsdns-hostmaster.amazon.com. 1 7200 900 1209600 86400',
                                ]),
                            ],
                        ]),
                    ]),
                ],
            ]),
        ]);

        $result = $client->createHostedZone($input);

        $result->resolve();

        $input = new ListResourceRecordSetsRequest([
            'HostedZoneId' => $hostedZoneId,
            'MaxItems' => '5',
        ]);
        $result = $client->listResourceRecordSets($input);

        $result->resolve();

        self::assertCount(2, $result->getResourceRecordSets());
        self::assertFalse($result->getIsTruncated());
        self::assertNull($result->getNextRecordName());
        self::assertSame('5', $result->getMaxItems());
    }

    private function getClient(): Route53Client
    {
        return new Route53Client([
            'endpoint' => 'http://localhost:4576',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
