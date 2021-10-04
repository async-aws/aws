<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Result\ListApiKeysResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListApiKeysResponseTest extends TestCase
{
    public function testListApiKeysResponse(): void
    {
        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_ListApiKeys.html
        $response = new SimpleMockedResponse('{
           "apiKeys": [
              {
                 "deletes": 1337,
                 "description": "This is the description",
                 "expires": 42,
                 "id": "identifier"
              }
           ],
           "nextToken": "token"
        }');

        $client = new MockHttpClient($response);
        $result = new ListApiKeysResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getApiKeys());
        self::assertEquals(1337, $result->getApiKeys()[0]->getDeletes());
        self::assertEquals(42, $result->getApiKeys()[0]->getExpires());
        self::assertEquals('This is the description', $result->getApiKeys()[0]->getDescription());
        self::assertEquals('identifier', $result->getApiKeys()[0]->getId());
        self::assertSame('token', $result->getnextToken());
    }
}
