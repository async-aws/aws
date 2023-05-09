<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Result\DetectModerationLabelsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DetectModerationLabelsResponseTest extends TestCase
{
    public function testDetectModerationLabelsResponse(): void
    {
        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_DetectModerationLabels.html
        $response = new SimpleMockedResponse('{
            "ModerationModelVersion": "6.1",
            "ModerationLabels": [
                {
                    "Confidence": 99.24723052978516,
                    "ParentName": "",
                    "Name": "Explicit Nudity"
                },
                {
                    "Confidence": 99.24723052978516,
                    "ParentName": "Explicit Nudity",
                    "Name": "Graphic Male Nudity"
                },
                {
                    "Confidence": 88.25341796875,
                    "ParentName": "Explicit Nudity",
                    "Name": "Sexual Activity"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new DetectModerationLabelsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('6.1', $result->getModerationModelVersion());
        self::assertCount(3, $result->getModerationLabels());

        self::assertSame(99.24723052978516, $result->getModerationLabels()[0]->getConfidence());
        self::assertSame('', $result->getModerationLabels()[0]->getParentName());
        self::assertSame('Explicit Nudity', $result->getModerationLabels()[0]->getName());

        self::assertSame(99.24723052978516, $result->getModerationLabels()[1]->getConfidence());
        self::assertSame('Explicit Nudity', $result->getModerationLabels()[1]->getParentName());
        self::assertSame('Graphic Male Nudity', $result->getModerationLabels()[1]->getName());

        self::assertSame(88.25341796875, $result->getModerationLabels()[2]->getConfidence());
        self::assertSame('Explicit Nudity', $result->getModerationLabels()[2]->getParentName());
        self::assertSame('Sexual Activity', $result->getModerationLabels()[2]->getName());
    }
}
