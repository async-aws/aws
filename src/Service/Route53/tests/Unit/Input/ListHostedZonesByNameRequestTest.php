<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Input\ListHostedZonesByNameRequest;

class ListHostedZonesByNameRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListHostedZonesByNameRequest([
            'DNSName' => 'example.com',
            'HostedZoneId' => 'Z1PA6795UKMFR9',
            'MaxItems' => '5',
        ]);

        // see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ListHostedZonesByName.html
        $expected = '
            GET /2013-04-01/hostedzonesbyname?dnsname=example.com&hostedzoneid=Z1PA6795UKMFR9&maxitems=5 HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
