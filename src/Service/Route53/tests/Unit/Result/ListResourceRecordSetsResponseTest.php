<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Enum\RRType;
use AsyncAws\Route53\Input\ListResourceRecordSetsRequest;
use AsyncAws\Route53\Result\ListResourceRecordSetsResponse;
use AsyncAws\Route53\Route53Client;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListResourceRecordSetsResponseTest extends TestCase
{
    public function testListResourceRecordSetsResponse(): void
    {
        // see https://docs.aws.amazon.com/route53/latest/APIReference/API_ListResourceRecordSets.html
        $response = new SimpleMockedResponse('
        <ListResourceRecordSetsResponse xmlns="https://route53.amazonaws.com/doc/2013-04-01/">
           <ResourceRecordSets>
              <ResourceRecordSet>
                 <Name>example.com.</Name>
                 <Type>SOA</Type>
                 <TTL>900</TTL>
                 <ResourceRecords>
                    <ResourceRecord>
                       <Value>ns-2048.awsdns-64.net. hostmaster.awsdns.com. 1 7200 900 1209600 86400</Value>
                    </ResourceRecord>
                 </ResourceRecords>
              </ResourceRecordSet>
           </ResourceRecordSets>
           <IsTruncated>true</IsTruncated>
           <MaxItems>1</MaxItems>
           <NextRecordName>example.com.</NextRecordName>
           <NextRecordType>NS</NextRecordType>
        </ListResourceRecordSetsResponse>
        ');

        $client = new MockHttpClient($response);
        $result = new ListResourceRecordSetsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new Route53Client(), new ListResourceRecordSetsRequest([]));

        self::assertCount(1, $result->getResourceRecordSets());
        self::assertTrue($result->getIsTruncated());
        self::assertSame('example.com.', $result->getNextRecordName());
        self::assertSame(RRType::NS, $result->getNextRecordType());
        self::assertNull($result->getNextRecordIdentifier());
        self::assertSame('1', $result->getMaxItems());
    }
}
