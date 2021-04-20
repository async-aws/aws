<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Result\RecognizeCelebritiesResponse;
use AsyncAws\Rekognition\ValueObject\Celebrity;
use AsyncAws\Rekognition\ValueObject\ComparedFace;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class RecognizeCelebritiesResponseTest extends TestCase
{
    public function testRecognizeCelebritiesResponse(): void
    {
        // see https://docs.aws.amazon.com/rekognition/latest/APIReference/API_RecognizeCelebrities.html
        $response = new SimpleMockedResponse(
            '{
           "CelebrityFaces": [
              {
                 "Face": {
                    "BoundingBox": {
                       "Height": 0.50312501192093,
                       "Left": 0.15391620993614,
                       "Top": 0.23874999582767,
                       "Width": 0.73315119743347
                    },
                    "Confidence": 99.956825256348,
                    "Landmarks": [
                       {
                          "Type": "eyeLeft",
                          "X": 0.39486581087112,
                          "Y": 0.43422022461891
                       }
                    ],
                    "Pose": {
                       "Pitch": -4.4637117385864,
                       "Roll": -2.8221764564514,
                       "Yaw": 1.0213866233826
                    },
                    "Quality": {
                       "Brightness": 79.16707611084,
                       "Sharpness": 99.200508117676
                    }
                 },
                 "Id": "1XJ5dK1a",
                 "MatchConfidence": 100.0,
                 "Name": "Robert Redford",
                 "Urls": [ "www.imdb.com/name/nm0000602" ]
              }
           ],
           "OrientationCorrection": "ROTATE_0",
           "UnrecognizedFaces": [
              {
                 "BoundingBox": {
                    "Height": 0.14778324961662,
                    "Left": 0.86863708496094,
                    "Top": 0.22167487442493,
                    "Width": 0.10016420483589
                 },
                 "Confidence": 99.999389648438,
                 "Landmarks": [
                    {
                       "Type": "eyeLeft",
                       "X": 0.90478724241257,
                       "Y": 0.27174332737923
                    }
                 ],
                 "Pose": {
                    "Pitch": 9.6288928985596,
                    "Roll": 20.020984649658,
                    "Yaw": -26.553016662598
                 },
                 "Quality": {
                    "Brightness": 69.114776611328,
                    "Sharpness": 60.634769439697
                 }
              }
           ]
        }'
        );

        $client = new MockHttpClient($response);
        $result = new RecognizeCelebritiesResponse(
            new Response($client->request('POST', 'http://localhost'), $client, new NullLogger())
        );

        $expectedGetCelebrityFaces = [
            new Celebrity(
                [
                    'Face' => [
                        'BoundingBox' => [
                            'Height' => 0.50312501192093,
                            'Left' => 0.15391620993614,
                            'Top' => 0.23874999582767,
                            'Width' => 0.73315119743347,
                        ],
                        'Confidence' => 99.956825256348,
                        'Landmarks' => [
                            [
                                'Type' => 'eyeLeft',
                                'X' => 0.39486581087112,
                                'Y' => 0.43422022461891,
                            ],
                        ],
                        'Pose' => [
                            'Pitch' => -4.4637117385864,
                            'Roll' => -2.8221764564514,
                            'Yaw' => 1.0213866233826,
                        ],
                        'Quality' => [
                            'Brightness' => 79.16707611084,
                            'Sharpness' => 99.200508117676,
                        ],
                    ],
                    'Id' => '1XJ5dK1a',
                    'MatchConfidence' => 100.0,
                    'Name' => 'Robert Redford',
                    'Urls' => ['www.imdb.com/name/nm0000602'],
                ]
            ),

        ];
        self::assertEquals($expectedGetCelebrityFaces, $result->getCelebrityFaces());

        $expectedGetUnrecognizedFaces = [
            new ComparedFace(
                [
                    'BoundingBox' => [
                        'Height' => 0.14778324961662,
                        'Left' => 0.86863708496094,
                        'Top' => 0.22167487442493,
                        'Width' => 0.10016420483589,
                    ],
                    'Confidence' => 99.999389648438,
                    'Landmarks' => [
                        [
                            'Type' => 'eyeLeft',
                            'X' => 0.90478724241257,
                            'Y' => 0.27174332737923,
                        ],
                    ],
                    'Pose' => [
                        'Pitch' => 9.6288928985596,
                        'Roll' => 20.020984649658,
                        'Yaw' => -26.553016662598,
                    ],
                    'Quality' => [
                        'Brightness' => 69.114776611328,
                        'Sharpness' => 60.634769439697,
                    ],
                ]
            ),

        ];
        self::assertEquals($expectedGetUnrecognizedFaces, $result->getUnrecognizedFaces());
    }
}
