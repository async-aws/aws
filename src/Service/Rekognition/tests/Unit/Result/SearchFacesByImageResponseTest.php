<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Result\SearchFacesByImageResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class SearchFacesByImageResponseTest extends TestCase
{
    public function testSearchFacesByImageResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "FaceMatches": [
                {
                    "Face": {
                        "BoundingBox": {
                            "Height": 0.3234420120716095,
                            "Left": 0.3233329951763153,
                            "Top": 0.5,
                            "Width": 0.24222199618816376
                        },
                        "Confidence": 99.99829864501953,
                        "FaceId": "38271d79-7bc2-5efb-b752-398a8d575b85",
                        "ImageId": "d5631190-d039-54e4-b267-abd22c8647c5"
                    },
                    "Similarity": 99.97036743164062
                }
            ],
            "SearchedFaceBoundingBox": {
                "Height": 0.33481481671333313,
                "Left": 0.31888890266418457,
                "Top": 0.4933333396911621,
                "Width": 0.25
            },
            "SearchedFaceConfidence": 99.9991226196289
        }');

        $client = new MockHttpClient($response);
        $result = new SearchFacesByImageResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(99.9991226196289, $result->getSearchedFaceConfidence());
        self::assertCount(1, $result->getFaceMatches());
        self::assertSame('38271d79-7bc2-5efb-b752-398a8d575b85', $result->getFaceMatches()[0]->getFace()->getFaceId());
    }
}
