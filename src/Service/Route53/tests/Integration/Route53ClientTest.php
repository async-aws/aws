<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Enum\ChangeAction;
use AsyncAws\Route53\Enum\ChangeStatus;
use AsyncAws\Route53\Enum\RRType;
use AsyncAws\Route53\Input\ChangeResourceRecordSetsRequest;
use AsyncAws\Route53\Input\CreateHostedZoneRequest;
use AsyncAws\Route53\Input\DeleteHostedZoneRequest;
use AsyncAws\Route53\Input\ListHostedZonesByNameRequest;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
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
        $client = $this->getClient();

        $this->deleteZone('bar-domain.com.');
        $input = new CreateHostedZoneRequest([
            'Name' => 'bar-domain.com',
            'CallerReference' => microtime(),
        ]);
        $result = $client->createHostedZone($input);
        $result->resolve();

        $input = new ChangeResourceRecordSetsRequest([
            'HostedZoneId' => $result->getHostedZone()->getId(),
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

        $result = $client->ChangeResourceRecordSets($input);
        $result->resolve();

        self::assertSame(ChangeStatus::INSYNC, $result->getChangeInfo()->getStatus());
    }

    public function testCreateHostedZone(): void
    {
        $client = $this->getClient();

        $this->deleteZone('test-domain.com.');
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

        self::assertSame('test-domain.com.', $result->getHostedZone()->getName());
        self::assertSame('foo', $result->getHostedZone()->getConfig()->getComment());
        self::assertFalse($result->getHostedZone()->getConfig()->getPrivateZone());
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

        $zonesBefore = \count(iterator_to_array($client->ListHostedZones()->getHostedZones()));

        $input = new DeleteHostedZoneRequest([
            'Id' => $result->getHostedZone()->getId(),
        ]);
        $result = $client->deleteHostedZone($input);
        $result->resolve();

        $zonesAfter = \count(iterator_to_array($client->ListHostedZones()->getHostedZones()));
        self::assertSame($zonesBefore - 1, $zonesAfter);
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

    public function testListHostedZonesByName(): void
    {
        $client = $this->getClient();
        $this->deleteZone('baz-domain.com.');

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

        $result = $client->changeResourceRecordSets($input);
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
    }

    public function testResourceRecordSetsChanged(): void
    {
        $client = $this->getClient();

        $input = new CreateHostedZoneRequest([
            'Name' => 'bar-domain.com',
            'CallerReference' => microtime(),
        ]);
        $result = $client->createHostedZone($input);
        $result->resolve();

        $input = new ChangeResourceRecordSetsRequest([
            'HostedZoneId' => $result->getHostedZone()->getId(),
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
                ],
            ]),
        ]);
        $result = $client->changeResourceRecordSets($input);
        $result->resolve();

        $waiter = $client->resourceRecordSetsChanged(['Id' => $result->getChangeInfo()->getId()]);

        self::assertTrue($waiter->wait());
        self::assertTrue($waiter->isSuccess());
    }

    private function deleteZone(string $domain): void
    {
        $client = $this->getClient();

        foreach ($client->ListHostedZones()->getHostedZones() as $zone) {
            if ($zone->getName() === $domain) {
                $client->deleteHostedZone(['Id' => $zone->getId()]);
            }
        }
    }

    private function getClient(): Route53Client
    {
        return new Route53Client([
            'endpoint' => 'http://localhost:4576',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
