<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\SearchFacesByImageRequest;
use AsyncAws\Rekognition\ValueObject\Image;
use AsyncAws\Rekognition\ValueObject\S3Object;

class SearchFacesByImageRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

                $input = new SearchFacesByImageRequest([
                            'CollectionId' => 'change me',
        'Image' => new Image([
                            'Bytes' => 'change me',
        'S3Object' => new S3Object([
                            'Bucket' => 'change me',
        'Name' => 'change me',
        'Version' => 'change me',
                        ]),
                        ]),
        'MaxFaces' => 1337,
        'FaceMatchThreshold' => 1337,
        'QualityFilter' => 'change me',
                        ]);

                // see example-1.json from SDK
                $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "CollectionId": "myphotos",
            "FaceMatchThreshold": 95,
            "Image": {
                "S3Object": {
                    "Bucket": "mybucket",
                    "Name": "myphoto"
                }
            },
            "MaxFaces": 5
        }
                ';

                self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
