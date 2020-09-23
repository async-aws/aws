<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\DetectFacesRequest;

class DetectFacesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DetectFacesRequest([
            'Image' => [
                'Bytes' => 'base64',
            ],
            'Attributes' => ['DEFAULT'],
        ]);
        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_DetectFaces.html

        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-Amz-Target: RekognitionService.DetectFaces

                {
                    "Image": {
                        "Bytes": "YmFzZTY0"
                    },
                    "Attributes": ["DEFAULT"]
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
