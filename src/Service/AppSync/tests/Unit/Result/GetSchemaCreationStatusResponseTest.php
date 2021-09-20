<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Result\GetSchemaCreationStatusResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetSchemaCreationStatusResponseTest extends TestCase
{
    public function testGetSchemaCreationStatusResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_GetSchemaCreationStatus.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new GetSchemaCreationStatusResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getstatus());
        self::assertSame('changeIt', $result->getdetails());
    }
}
