<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Result\AddLayerVersionPermissionResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AddLayerVersionPermissionResponseTest extends TestCase
{
    public function testAddLayerVersionPermissionResponse(): void
    {
        // see https://docs.aws.amazon.com/lambda/latest/dg/API_AddLayerVersionPermission.html
        $response = new SimpleMockedResponse('{
            "RevisionId": "123456",
            "Statement": "fooBar"
        }');

        $client = new MockHttpClient($response);
        $result = new AddLayerVersionPermissionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('fooBar', $result->getStatement());
        self::assertSame('123456', $result->getRevisionId());
    }
}
