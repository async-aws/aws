<?php

namespace AsyncAws\Route53\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Input\ListHostedZonesRequest;

class ListHostedZonesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListHostedZonesRequest([
            'MaxItems' => '1',
        ]);

        // see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ListHostedZones.html
        $expected = '
            GET /2013-04-01/hostedzone?maxitems=1 HTTP/1.0
            Content-Type: application/xml

        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
