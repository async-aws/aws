<?php

namespace AsyncAws\Route53\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
use AsyncAws\Route53\Result\ListHostedZonesResponse;
use AsyncAws\Route53\Route53Client;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListHostedZonesResponseTest extends TestCase
{
    public function testListHostedZonesResponse(): void
    {
        // see https://docs.aws.amazon.com/route53/latest/APIReference/API_ListHostedZones.html
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
<ListHostedZonesResponse xmlns="https://route53.amazonaws.com/doc/2013-04-01/">
   <HostedZones>
      <HostedZone>
         <Id>/hostedzone/Z111111QQQQQQQ</Id>
         <Name>example.com.</Name>
         <CallerReference>MyUniqueIdentifier1</CallerReference>
         <Config>
            <Comment>This is my first hosted zone.</Comment>
            <PrivateZone>false</PrivateZone>
         </Config>
         <ResourceRecordSetCount>42</ResourceRecordSetCount>
      </HostedZone>
   </HostedZones>
   <IsTruncated>true</IsTruncated>
   <NextMarker>Z222222VVVVVVV</NextMarker>
   <MaxItems>1</MaxItems>
</ListHostedZonesResponse>');

        $client = new MockHttpClient($response);
        $result = new ListHostedZonesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new Route53Client(), new ListHostedZonesRequest([]));

        $zones = iterator_to_array($result->getHostedZones(true));
        self::assertCount(1, $zones);
        $hostedZone = $zones[0];

        self::assertSame('/hostedzone/Z111111QQQQQQQ', $hostedZone->getId());
        self::assertSame('example.com.', $hostedZone->getName());
        self::assertSame('This is my first hosted zone.', $hostedZone->getConfig()->getComment());
        self::assertSame('Z222222VVVVVVV', $result->getNextMarker());
        self::assertSame('1', $result->getMaxItems());
    }
}
