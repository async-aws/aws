<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\UpdateTimeToLiveOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class UpdateTimeToLiveOutputTest extends TestCase
{
    public function testUpdateTimeToLiveOutput(): void
    {
        // see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_UpdateTimeToLive.html
        $response = new SimpleMockedResponse('{
           "TimeToLiveSpecification": {
              "AttributeName": "attribute",
              "Enabled": true
           }
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateTimeToLiveOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('attribute', $result->getTimeToLiveSpecification()->getAttributeName());
        self::assertSame(true, $result->getTimeToLiveSpecification()->getEnabled());
    }
}
