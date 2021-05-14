<?php

namespace AsyncAws\Route53\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
use AsyncAws\Route53\Result\ListHostedZonesResponse;
use AsyncAws\Route53\Route53Client;
use Symfony\Component\HttpClient\MockHttpClient;

class Route53ClientTest extends TestCase
{
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
