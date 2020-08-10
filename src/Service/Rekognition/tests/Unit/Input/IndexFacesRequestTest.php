<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\IndexFacesRequest;
use AsyncAws\Rekognition\ValueObject\Image;
use AsyncAws\Rekognition\ValueObject\S3Object;

class IndexFacesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

                $input = new IndexFacesRequest([
                            'CollectionId' => 'change me',
        'Image' => new Image([
                            'Bytes' => 'change me',
        'S3Object' => new S3Object([
                            'Bucket' => 'change me',
        'Name' => 'change me',
        'Version' => 'change me',
                        ]),
                        ]),
        'ExternalImageId' => 'change me',
        'DetectionAttributes' => ['change me'],
        'MaxFaces' => 1337,
        'QualityFilter' => 'change me',
                        ]);

                // see example-1.json from SDK
                $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "CollectionId": "myphotos",
            "DetectionAttributes": [],
            "ExternalImageId": "myphotoid",
            "Image": {
                "S3Object": {
                    "Bucket": "mybucket",
                    "Name": "myphoto"
                }
            }
        }
                ';

                self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
