<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Result\DeleteCollectionResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteCollectionResponseTest extends TestCase
{
    public function testDeleteCollectionResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "StatusCode": 200
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteCollectionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(200, $result->getStatusCode());
    }
}
