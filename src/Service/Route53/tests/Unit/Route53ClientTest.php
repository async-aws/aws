<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Enum\ChangeAction;
use AsyncAws\Route53\Enum\RRType;
use AsyncAws\Route53\Input\ChangeResourceRecordSetsRequest;
use AsyncAws\Route53\Input\CreateHostedZoneRequest;
use AsyncAws\Route53\Input\DeleteHostedZoneRequest;
use AsyncAws\Route53\Input\ListHostedZonesByNameRequest;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
use AsyncAws\Route53\Input\ListResourceRecordSetsRequest;
use AsyncAws\Route53\Result\ChangeResourceRecordSetsResponse;
use AsyncAws\Route53\Result\CreateHostedZoneResponse;
use AsyncAws\Route53\Result\DeleteHostedZoneResponse;
use AsyncAws\Route53\Result\ListHostedZonesByNameResponse;
use AsyncAws\Route53\Result\ListHostedZonesResponse;
use AsyncAws\Route53\Result\ListResourceRecordSetsResponse;
use AsyncAws\Route53\Route53Client;
use AsyncAws\Route53\ValueObject\Change;
use AsyncAws\Route53\ValueObject\ChangeBatch;
use AsyncAws\Route53\ValueObject\ResourceRecordSet;
use Symfony\Component\HttpClient\MockHttpClient;

class Route53ClientTest extends TestCase
{
    public function testChangeResourceRecordSets(): void
    {
        $client = new Route53Client([], new NullProvider(), new MockHttpClient());

        $input = new ChangeResourceRecordSetsRequest([
            'HostedZoneId' => 'Z1PA6795UKMFR9',
            'ChangeBatch' => new ChangeBatch([
                'Changes' => [
                    new Change([
                        'Action' => ChangeAction::CREATE,
                        'ResourceRecordSet' => new ResourceRecordSet([
                            'Name' => 'Main',
                            'Type' => RRType::A,
                        ]),
                    ]),
                ],
            ]),
        ]);
        $result = $client->changeResourceRecordSets($input);

        self::assertInstanceOf(ChangeResourceRecordSetsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateHostedZone(): void
    {
        $client = new Route53Client([], new NullProvider(), new MockHttpClient());

        $input = new CreateHostedZoneRequest([
            'Name' => 'example.com',
            'CallerReference' => 'uniqueId',

        ]);
        $result = $client->createHostedZone($input);

        self::assertInstanceOf(CreateHostedZoneResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListHostedZones(): void
    {
        $client = new Route53Client([], new NullProvider(), new MockHttpClient());

        $input = new ListHostedZonesRequest([

        ]);
        $result = $client->ListHostedZones($input);

        self::assertInstanceOf(ListHostedZonesResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteHostedZone(): void
    {
        $client = new Route53Client([], new NullProvider(), new MockHttpClient());

        $input = new DeleteHostedZoneRequest([
            'Id' => 'Z1PA6795UKMFR9',
        ]);
        $result = $client->deleteHostedZone($input);

        self::assertInstanceOf(DeleteHostedZoneResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListHostedZonesByName(): void
    {
        $client = new Route53Client([], new NullProvider(), new MockHttpClient());

        $input = new ListHostedZonesByNameRequest([

        ]);
        $result = $client->listHostedZonesByName($input);

        self::assertInstanceOf(ListHostedZonesByNameResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListResourceRecordSets(): void
    {
        $client = new Route53Client([], new NullProvider(), new MockHttpClient());

        $input = new ListResourceRecordSetsRequest([
            'HostedZoneId' => 'Z1PA6795UKMFR9',
        ]);
        $result = $client->listResourceRecordSets($input);

        self::assertInstanceOf(ListResourceRecordSetsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
