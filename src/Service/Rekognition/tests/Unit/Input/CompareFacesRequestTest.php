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
        $input = new CompareFacesRequest([
            'SourceImage' => new Image([
                'Bytes' => 'first-image-bytes',
                'S3Object' => new S3Object([
                    'Bucket' => 'bucket1',
                    'Name' => 'my-bucket-1',
                    'Version' => 'stable',
                ]),
            ]),
            'TargetImage' => new Image([
                'Bytes' => 'second-image-bytes',
                'S3Object' => new S3Object([
                    'Bucket' => 'bucket2',
                    'Name' => 'my-bucket-2',
                    'Version' => 'stable',
                ]),
            ]),
            'SimilarityThreshold' => 90,
            'QualityFilter' => 'AUTO',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: RekognitionService.CompareFaces
            Accept: application/json

            {
                "QualityFilter": "AUTO",
                "SimilarityThreshold": 90,
                "SourceImage": {
                    "Bytes": "Zmlyc3QtaW1hZ2UtYnl0ZXM=",
                    "S3Object": {
                        "Bucket": "bucket1",
                        "Name": "my-bucket-1",
                        "Version": "stable"
                    }
                },
                "TargetImage": {
                    "Bytes": "c2Vjb25kLWltYWdlLWJ5dGVz",
                    "S3Object": {
                        "Bucket": "bucket2",
                        "Name": "my-bucket-2",
                        "Version": "stable"
                    }
                }
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
