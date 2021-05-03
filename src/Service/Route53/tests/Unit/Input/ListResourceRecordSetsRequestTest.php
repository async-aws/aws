<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Enum\RRType;
use AsyncAws\Route53\Input\ListResourceRecordSetsRequest;

class ListResourceRecordSetsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListResourceRecordSetsRequest([
            'HostedZoneId' => 'Z1PA6795UKMFR9',
            'StartRecordName' => 'Main',
            'StartRecordType' => RRType::A,
            'MaxItems' => '100',
        ]);

        // see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ListResourceRecordSets.html
        $expected = '
            GET /2013-04-01/hostedzone/Z1PA6795UKMFR9/rrset?name=Main&type=A&maxitems=100 HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
