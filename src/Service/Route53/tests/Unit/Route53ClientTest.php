<?php

namespace AsyncAws\Route53\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Input\CreateHostedZoneRequest;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
use AsyncAws\Route53\Result\CreateHostedZoneResponse;
use AsyncAws\Route53\Result\ListHostedZonesResponse;
use AsyncAws\Route53\Route53Client;
use Symfony\Component\HttpClient\MockHttpClient;

class Route53ClientTest extends TestCase
{
    public function testCreateHostedZone(): void
    {
        $client = new Route53Client([], new NullProvider(), new MockHttpClient());

        $input = new CreateHostedZoneRequest([
            'Name' => 'change me',

            'CallerReference' => 'change me',

        ]);
        $result = $client->CreateHostedZone($input);

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
}
