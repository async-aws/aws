<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListEventSourceMappingsRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\Result\ListEventSourceMappingsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListEventSourceMappingsResponseTest extends TestCase
{
    public function testListEventSourceMappingsResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "EventSourceMappings": [
                {
                    "BatchSize": 5,
                    "EventSourceArn": "arn:aws:sqs:us-west-2:123456789012:mySQSqueue",
                    "FunctionArn": "arn:aws:lambda:us-west-2:123456789012:function:my-function",
                    "LastModified": 1569284520.333,
                    "State": "Enabled",
                    "StateTransitionReason": "USER_INITIATED",
                    "UUID": "a1b2c3d4-5678-90ab-cdef-11111EXAMPLE"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListEventSourceMappingsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new LambdaClient(), new ListEventSourceMappingsRequest([]));

        self::assertNull($result->getNextMarker());

        foreach ($result->getEventSourceMappings(true) as $mapping) {
            self::assertSame(5, $mapping->getBatchSize());
            self::assertSame('arn:aws:sqs:us-west-2:123456789012:mySQSqueue', $mapping->getEventSourceArn());
            self::assertSame('arn:aws:lambda:us-west-2:123456789012:function:my-function', $mapping->getFunctionArn());
            self::assertSame('Enabled', $mapping->getState());
            self::assertSame('USER_INITIATED', $mapping->getStateTransitionReason());

            break;
        }
    }
}
