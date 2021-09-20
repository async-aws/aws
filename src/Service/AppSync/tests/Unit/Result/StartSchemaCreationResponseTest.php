<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Result\StartSchemaCreationResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StartSchemaCreationResponseTest extends TestCase
{
    public function testStartSchemaCreationResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_StartSchemaCreation.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new StartSchemaCreationResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getstatus());
    }
}
