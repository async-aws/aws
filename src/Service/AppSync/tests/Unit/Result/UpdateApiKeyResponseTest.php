<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Result\UpdateApiKeyResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class UpdateApiKeyResponseTest extends TestCase
{
    public function testUpdateApiKeyResponse(): void
    {
        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateApiKey.html
        $response = new SimpleMockedResponse('{
           "apiKey": {
              "deletes": 42,
              "description": "Description here",
              "expires": 1337,
              "id": "identifier"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateApiKeyResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $apiKey = $result->getApiKey();
        self::assertNotNull($apiKey);
        self::assertEquals(42, $apiKey->getDeletes());
        self::assertEquals(1337, $apiKey->getExpires());
        self::assertEquals('identifier', $apiKey->getId());
        self::assertEquals('Description here', $apiKey->getDescription());
    }
}
