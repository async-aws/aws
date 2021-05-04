<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\ListCollectionsRequest;
use AsyncAws\Rekognition\RekognitionClient;
use AsyncAws\Rekognition\Result\ListCollectionsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListCollectionsResponseTest extends TestCase
{
    public function testListCollectionsResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "CollectionIds": [
                "myphotos"
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListCollectionsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new RekognitionClient(), new ListCollectionsRequest([]));

        self::assertSame(['myphotos'], iterator_to_array($result->getCollectionIds()));
    }
}
