<?php

namespace AsyncAws\Route53\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
use AsyncAws\Route53\Route53Client;

class Route53ClientTest extends TestCase
{
    public function testListHostedZones(): void
    {
        $client = $this->getClient();

        $input = new ListHostedZonesRequest([
        ]);
        $result = $client->ListHostedZones($input);

        $result->resolve();

        $zones = iterator_to_array($result->getHostedZones());
        self::assertCount(0, $zones);
        self::assertFalse($result->getIsTruncated());
    }

    private function getClient(): Route53Client
    {
        return new Route53Client([
            'endpoint' => 'http://localhost:4576',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
