<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\CompareFacesRequest;
use AsyncAws\Rekognition\ValueObject\Image;
use AsyncAws\Rekognition\ValueObject\S3Object;

class CompareFacesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new CompareFacesRequest([
            'SourceImage' => new Image([
                'Bytes' => 'change me',
                'S3Object' => new S3Object([
                    'Bucket' => 'change me',
                    'Name' => 'change me',
                    'Version' => 'change me',
                ]),
            ]),
            'TargetImage' => new Image([
                'Bytes' => 'change me',
                'S3Object' => new S3Object([
                    'Bucket' => 'change me',
                    'Name' => 'change me',
                    'Version' => 'change me',
                ]),
            ]),
            'SimilarityThreshold' => 1337,
            'QualityFilter' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "SimilarityThreshold": 90,
            "SourceImage": {
                "S3Object": {
                    "Bucket": "mybucket",
                    "Name": "mysourceimage"
                }
            },
            "TargetImage": {
                "S3Object": {
                    "Bucket": "mybucket",
                    "Name": "mytargetimage"
                }
            }
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
