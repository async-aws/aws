<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Result\ListHostedZonesByNameResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListHostedZonesByNameResponseTest extends TestCase
{
    public function testListHostedZonesByNameResponse(): void
    {
        // see https://docs.aws.amazon.com/route53/latest/APIReference/API_ListHostedZonesByName.html
        $response = new SimpleMockedResponse('
        <ListHostedZonesByNameResponse xmlns="https://route53.amazonaws.com/doc/2013-04-01/">
           <HostedZones>
              <HostedZone>
                 <Id>/hostedzone/Z111111QQQQQQQ</Id>
                 <Name>example.com.</Name>
                 <CallerReference>uniqueId</CallerReference>
                 <Config>
                    <Comment>foo</Comment>
                    <PrivateZone>false</PrivateZone>
                 </Config>
                 <ResourceRecordSetCount>42</ResourceRecordSetCount>
              </HostedZone>
           </HostedZones>
           <IsTruncated>true</IsTruncated>
           <NextDNSName>example2.com</NextDNSName>
           <NextHostedZoneId>Z222222VVVVVVV</NextHostedZoneId>
           <MaxItems>1</MaxItems>
        </ListHostedZonesByNameResponse>
        ');

        $client = new MockHttpClient($response);
        $result = new ListHostedZonesByNameResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getHostedZones());
        self::assertTrue($result->getIsTruncated());
        self::assertSame('example2.com', $result->getNextDNSName());
        self::assertSame('Z222222VVVVVVV', $result->getNextHostedZoneId());
        self::assertSame('1', $result->getMaxItems());
    }
}
