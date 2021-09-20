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
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateApiKey.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateApiKeyResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        // self::assertTODO(expected, $result->getapiKey());
    }
}
