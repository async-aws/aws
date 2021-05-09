<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Enum\ChangeAction;
use AsyncAws\Route53\Enum\ResourceRecordSetFailover;
use AsyncAws\Route53\Enum\RRType;
use AsyncAws\Route53\Input\ChangeResourceRecordSetsRequest;
use AsyncAws\Route53\ValueObject\Change;
use AsyncAws\Route53\ValueObject\ChangeBatch;
use AsyncAws\Route53\ValueObject\ResourceRecord;
use AsyncAws\Route53\ValueObject\ResourceRecordSet;

class ChangeResourceRecordSetsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ChangeResourceRecordSetsRequest([
            'HostedZoneId' => 'Z1D633PEXAMPLE',
            'ChangeBatch' => new ChangeBatch([
                'Comment' => 'foo',
                'Changes' => [
                    new Change([
                        'Action' => ChangeAction::UPSERT,
                        'ResourceRecordSet' => new ResourceRecordSet([
                            'Name' => 'www.example.com',
                            'Type' => RRType::A,
                            'SetIdentifier' => 'Main',
                            'Weight' => 100,
                            'Failover' => ResourceRecordSetFailover::PRIMARY,
                            'TTL' => 60,
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

        // see example-1.json from SDK
        $expected = '
POST /2013-04-01/hostedzone/Z1D633PEXAMPLE/rrset/ HTTP/1.0
Content-Type: application/xml
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
