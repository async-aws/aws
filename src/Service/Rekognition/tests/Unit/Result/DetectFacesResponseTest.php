<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Enum\OrientationCorrection;
use AsyncAws\Rekognition\Result\DetectFacesResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DetectFacesResponseTest extends TestCase
{
    public function testDetectFacesResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "FaceDetails": [
                {
                    "BoundingBox": {
                        "Height": 0.18000000715255737,
                        "Left": 0.5555555820465088,
                        "Top": 0.33666667342185974,
                        "Width": 0.23999999463558197
                    },
                    "Confidence": 100,
                    "Landmarks": [
                        {
                            "Type": "eyeLeft",
                            "X": 0.6394737362861633,
                            "Y": 0.40819624066352844
                        },
                        {
                            "Type": "eyeRight",
                            "X": 0.7266660928726196,
                            "Y": 0.41039225459098816
                        },
                        {
                            "Type": "eyeRight",
                            "X": 0.6912462115287781,
                            "Y": 0.44240960478782654
                        },
                        {
                            "Type": "mouthDown",
                            "X": 0.6306198239326477,
                            "Y": 0.46700039505958557
                        },
                        {
                            "Type": "mouthUp",
                            "X": 0.7215608954429626,
                            "Y": 0.47114261984825134
                        }
                    ],
                    "Pose": {
                        "Pitch": 4.050806522369385,
                        "Roll": 0.9950747489929199,
                        "Yaw": 13.693790435791016
                    },
                    "Quality": {
                        "Brightness": 37.60169982910156,
                        "Sharpness": 80
                    }
                }
            ],
            "OrientationCorrection": "ROTATE_0"
        }');

        $client = new MockHttpClient($response);
        $result = new DetectFacesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        // self::assertTODO(expected, $result->getFaceDetails());
        self::assertSame(OrientationCorrection::ROTATE_0, $result->getOrientationCorrection());
        self::assertCount(1, $result->getFaceDetails());
        self::assertSame(100.0, $result->getFaceDetails()[0]->getConfidence());
        self::assertSame(37.60169982910156, $result->getFaceDetails()[0]->getQuality()->getBrightness());
        self::assertSame(0.18000000715255737, $result->getFaceDetails()[0]->getBoundingBox()->getHeight());
    }
}
