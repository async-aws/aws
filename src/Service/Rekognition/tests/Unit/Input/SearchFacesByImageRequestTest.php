<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Enum\QualityFilter;
use AsyncAws\Rekognition\Input\SearchFacesByImageRequest;
use AsyncAws\Rekognition\ValueObject\Image;

class SearchFacesByImageRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SearchFacesByImageRequest([
            'CollectionId' => 'myCollectionId',
            'Image' => new Image([
                'Bytes' => 'imaginary file content',
            ]),
            'MaxFaces' => 2,
            'FaceMatchThreshold' => 80.5,
            'QualityFilter' => QualityFilter::MEDIUM,
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_SearchFacesByImage.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-Amz-Target: RekognitionService.SearchFacesByImage

                {
                    "Image": {
                        "Bytes": "aW1hZ2luYXJ5IGZpbGUgY29udGVudA=="
                    },
                    "CollectionId": "myCollectionId",
                    "FaceMatchThreshold": 80.5,
                    "QualityFilter": "MEDIUM",
                    "MaxFaces": 2
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
