<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\IndexFacesRequest;

class IndexFacesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new IndexFacesRequest([
            'CollectionId' => 'myCollectionId',
            'Image' => [
                'Bytes' => 'base64',
            ],
            'ExternalImageId' => 'myphotoid',
            'MaxFaces' => 1,
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_IndexFaces.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-Amz-Target: RekognitionService.IndexFaces

                {
                    "Image": {
                        "Bytes": "YmFzZTY0"
                    },
                    "CollectionId": "myCollectionId",
                    "ExternalImageId": "myphotoid",
                    "MaxFaces": 1
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
