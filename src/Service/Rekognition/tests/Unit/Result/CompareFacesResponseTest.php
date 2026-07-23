<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Result\CompareFacesResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CompareFacesResponseTest extends TestCase
{
    public function testCompareFacesResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "FaceMatches": [
                {
                    "Face": {
                        "BoundingBox": {
                            "Height": 0.33481481671333313,
                            "Left": 0.31888890266418457,
                            "Top": 0.4933333396911621,
                            "Width": 0.25
                        },
                        "Confidence": 99.9991226196289
                    },
                    "Similarity": 100
                }
            ],
            "SourceImageFace": {
                "BoundingBox": {
                    "Height": 0.33481481671333313,
                    "Left": 0.31888890266418457,
                    "Top": 0.4933333396911621,
                    "Width": 0.25
                },
                "Confidence": 99.9991226196289
            }
        }');

        $client = new MockHttpClient($response);
        $result = new CompareFacesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(99.9991226196289, $result->getSourceImageFace()->getConfidence());
        self::assertSame(0.33481481671333313, $result->getSourceImageFace()->getBoundingBox()->getHeight());
        self::assertCount(1, $result->getFaceMatches());
        self::assertSame(100.0, $result->getFaceMatches()[0]->getSimilarity());
        self::assertSame(99.9991226196289, $result->getFaceMatches()[0]->getFace()->getConfidence());
        self::assertCount(0, $result->getUnmatchedFaces());
        self::assertNull($result->getSourceImageOrientationCorrection());
        self::assertNull($result->getTargetImageOrientationCorrection());
    }
}
