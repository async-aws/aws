<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Result\AddLayerVersionPermissionResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class AddLayerVersionPermissionResponseTest extends TestCase
{
    public function testAddLayerVersionPermissionResponse(): void
    {
        self::markTestIncomplete('Not implemented');

        // see https://docs.aws.amazon.com/SERVICE/latest/APIReference/API_METHOD.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new AddLayerVersionPermissionResponse($client->request('POST', 'http://localhost'), $client);

        self::assertSame('changeIt', $result->getStatement());
        self::assertSame('changeIt', $result->getRevisionId());
    }
}
