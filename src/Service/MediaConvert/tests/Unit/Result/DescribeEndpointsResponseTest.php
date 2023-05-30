<?php

namespace AsyncAws\MediaConvert\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Input\DescribeEndpointsRequest;
use AsyncAws\MediaConvert\MediaConvertClient;
use AsyncAws\MediaConvert\Result\DescribeEndpointsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeEndpointsResponseTest extends TestCase
{
    public function testDescribeEndpointsResponse(): void
    {
        // see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_DescribeEndpoints.html
        $response = new SimpleMockedResponse('{
            "endpoints": [
                {
                    "url": "http://account.localhost"
                }
            ],
            "nextToken": "fakeToken"
        }');

        $client = new MockHttpClient($response);
        $result = new DescribeEndpointsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new MediaConvertClient(), new DescribeEndpointsRequest([]));

        $endpoints = iterator_to_array($result->getEndpoints(true));
        self::assertCount(1, $endpoints);
        self::assertSame('http://account.localhost', $endpoints[0]->getUrl());
        self::assertSame('fakeToken', $result->getNextToken());
    }
}
