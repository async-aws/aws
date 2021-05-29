<?php

namespace AsyncAws\Route53\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Enum\ChangeStatus;
use AsyncAws\Route53\Result\CreateHostedZoneResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateHostedZoneResponseTest extends TestCase
{
    public function testCreateHostedZoneResponse(): void
    {
        // see https://docs.aws.amazon.com/Route53/latest/APIReference/API_CreateHostedZone.html
        $response = new SimpleMockedResponse('
<CreateHostedZoneResponse xmlns="https://route53.amazonaws.com/doc/2013-04-01/">
    <HostedZone>
        <Id>/hostedzone/Z1PA6795UKMFR9</Id>
        <Name>example.com.</Name>
        <CallerReference>uniqueId</CallerReference>
        <Config>
            <Comment>foo</Comment>
            <PrivateZone>false</PrivateZone>
        </Config>
        <ResourceRecordSetCount>2</ResourceRecordSetCount>
    </HostedZone>
    <ChangeInfo>
        <Id>/change/C1PA6795UKMFR9</Id>
        <Status>PENDING</Status>
        <SubmittedAt>2017-03-15T01:36:41.958Z</SubmittedAt>
    </ChangeInfo>
    <DelegationSet>
        <Id>NZ8X2CISAMPLE</Id>
        <NameServers>
            <NameServer>ns-2048.awsdns-64.com</NameServer>
            <NameServer>ns-2049.awsdns-65.net</NameServer>
            <NameServer>ns-2050.awsdns-66.org</NameServer>
            <NameServer>ns-2051.awsdns-67.co.uk</NameServer>
        </NameServers>
    </DelegationSet>
</CreateHostedZoneResponse>', ['location' => ['https://aws.com/hostedzone/Z1PA6795UKMFR9']]);

        $client = new MockHttpClient($response);
        $result = new CreateHostedZoneResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('https://aws.com/hostedzone/Z1PA6795UKMFR9', $result->getLocation());
        self::assertSame('/hostedzone/Z1PA6795UKMFR9', $result->getHostedZone()->getId());
        self::assertSame('example.com.', $result->getHostedZone()->getName());
        self::assertSame('uniqueId', $result->getHostedZone()->getCallerReference());
        self::assertFalse($result->getHostedZone()->getConfig()->getPrivateZone());
        self::assertSame(ChangeStatus::PENDING, $result->getChangeInfo()->getStatus());
        self::assertSame('NZ8X2CISAMPLE', $result->getDelegationSet()->getId());
        self::assertSame([
            'ns-2048.awsdns-64.com',
            'ns-2049.awsdns-65.net',
            'ns-2050.awsdns-66.org',
            'ns-2051.awsdns-67.co.uk',
        ], $result->getDelegationSet()->getNameServers());
    }
}
