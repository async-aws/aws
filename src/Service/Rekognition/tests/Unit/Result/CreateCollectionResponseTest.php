<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Result\CreateCollectionResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateCollectionResponseTest extends TestCase
{
    public function testCreateCollectionResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "CollectionArn": "aws:rekognition:us-west-2:123456789012:collection\\/myphotos",
            "StatusCode": 200
        }');

        $client = new MockHttpClient($response);
        $result = new CreateCollectionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(200, $result->getStatusCode());
        self::assertSame('aws:rekognition:us-west-2:123456789012:collection/myphotos', $result->getCollectionArn());
        self::assertNull($result->getFaceModelVersion());
    }
}
