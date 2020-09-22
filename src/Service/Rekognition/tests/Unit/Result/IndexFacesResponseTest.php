<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Enum\OrientationCorrection;
use AsyncAws\Rekognition\Result\IndexFacesResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class IndexFacesResponseTest extends TestCase
{
    public function testIndexFacesResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "FaceRecords": [
                {
                    "Face": {
                        "BoundingBox": {
                            "Height": 0.33481481671333313,
                            "Left": 0.31888890266418457,
                            "Top": 0.4933333396911621,
                            "Width": 0.25
                        },
                        "Confidence": 99.9991226196289,
                        "FaceId": "ff43d742-0c13-5d16-a3e8-03d3f58e980b",
                        "ImageId": "465f4e93-763e-51d0-b030-b9667a2d94b1"
                    },
                    "FaceDetail": {
                        "BoundingBox": {
                            "Height": 0.33481481671333313,
                            "Left": 0.31888890266418457,
                            "Top": 0.4933333396911621,
                            "Width": 0.25
                        },
                        "Confidence": 99.9991226196289,
                        "Landmarks": [
                            {
                                "Type": "eyeLeft",
                                "X": 0.3976764678955078,
                                "Y": 0.6248345971107483
                            },
                            {
                                "Type": "eyeRight",
                                "X": 0.4810936450958252,
                                "Y": 0.6317117214202881
                            },
                            {
                                "Type": "noseLeft",
                                "X": 0.41986238956451416,
                                "Y": 0.7111940383911133
                            },
                            {
                                "Type": "mouthDown",
                                "X": 0.40525302290916443,
                                "Y": 0.7497701048851013
                            },
                            {
                                "Type": "mouthUp",
                                "X": 0.4753248989582062,
                                "Y": 0.7558549642562866
                            }
                        ],
                        "Pose": {
                            "Pitch": -9.713645935058594,
                            "Roll": 4.707281112670898,
                            "Yaw": -24.438663482666016
                        },
                        "Quality": {
                            "Brightness": 29.23358917236328,
                            "Sharpness": 80
                        }
                    }
                },
                {
                    "Face": {
                        "BoundingBox": {
                            "Height": 0.32592591643333435,
                            "Left": 0.5144444704055786,
                            "Top": 0.15111111104488373,
                            "Width": 0.24444444477558136
                        },
                        "Confidence": 99.99950408935547,
                        "FaceId": "8be04dba-4e58-520d-850e-9eae4af70eb2",
                        "ImageId": "465f4e93-763e-51d0-b030-b9667a2d94b1"
                    },
                    "FaceDetail": {
                        "BoundingBox": {
                            "Height": 0.32592591643333435,
                            "Left": 0.5144444704055786,
                            "Top": 0.15111111104488373,
                            "Width": 0.24444444477558136
                        },
                        "Confidence": 99.99950408935547,
                        "Landmarks": [
                            {
                                "Type": "eyeLeft",
                                "X": 0.6006892323493958,
                                "Y": 0.290842205286026
                            },
                            {
                                "Type": "eyeRight",
                                "X": 0.6808141469955444,
                                "Y": 0.29609042406082153
                            },
                            {
                                "Type": "noseLeft",
                                "X": 0.6395332217216492,
                                "Y": 0.3522595763206482
                            },
                            {
                                "Type": "mouthDown",
                                "X": 0.5892083048820496,
                                "Y": 0.38689887523651123
                            },
                            {
                                "Type": "mouthUp",
                                "X": 0.674560010433197,
                                "Y": 0.394125759601593
                            }
                        ],
                        "Pose": {
                            "Pitch": -4.683138370513916,
                            "Roll": 2.1029529571533203,
                            "Yaw": 6.716655254364014
                        },
                        "Quality": {
                            "Brightness": 34.951698303222656,
                            "Sharpness": 160
                        }
                    }
                }
            ],
            "OrientationCorrection": "ROTATE_0"
        }');

        $client = new MockHttpClient($response);
        $result = new IndexFacesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        // self::assertTODO(expected, $result->getFaceRecords());
        self::assertSame(OrientationCorrection::ROTATE_0, $result->getOrientationCorrection());
        self::assertNull($result->getFaceModelVersion());
        self::assertCount(2, $result->getFaceRecords());
        self::assertSame('8be04dba-4e58-520d-850e-9eae4af70eb2', $result->getFaceRecords()[1]->getFace()->getFaceId());
        self::assertCount(5, $result->getFaceRecords()[1]->getFaceDetail()->getLandmarks());
        self::assertCount(0, $result->getUnindexedFaces());
        // self::assertTODO(expected, $result->getUnindexedFaces());
    }
}
