<?php

namespace AsyncAws\TimestreamWrite\Tests\Unit\Result;

use AsyncAws\Core\EndpointDiscovery\EndpointInterface;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamWrite\Result\DescribeEndpointsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeEndpointsResponseTest extends TestCase
{
    public function testDescribeEndpointsResponse(): void
    {
        // see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_DescribeEndpoints.html
        $response = new SimpleMockedResponse('{
           "Endpoints": [
              {
                 "Address": "www.aws.com",
                 "CachePeriodInMinutes": 1234
              }
           ]
        }');

        $client = new MockHttpClient($response);
        $result = new DescribeEndpointsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getEndpoints());
        self::assertInstanceOf(EndpointInterface::class, $result->getEndpoints()[0]);
        self::assertSame('www.aws.com', $result->getEndpoints()[0]->getAddress());
        self::assertSame(1234, $result->getEndpoints()[0]->getCachePeriodInMinutes());
    }
}
