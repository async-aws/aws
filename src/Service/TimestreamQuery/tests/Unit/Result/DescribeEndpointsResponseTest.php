<?php

namespace AsyncAws\TimestreamQuery\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamQuery\Result\DescribeEndpointsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeEndpointsResponseTest extends TestCase
{
    public function testDescribeEndpointsResponse(): void
    {
        $response = new SimpleMockedResponse('{
            "Endpoints": [
                {
                    "Address": "ingest-cell2.timestream.us-east-1.amazonaws.com",
                    "CachePeriodInMinutes": 1440
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new DescribeEndpointsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getEndpoints());
        self::assertSame('ingest-cell2.timestream.us-east-1.amazonaws.com', $result->getEndpoints()[0]->getAddress());
        self::assertSame('1440', $result->getEndpoints()[0]->getCachePeriodInMinutes());
    }
}
