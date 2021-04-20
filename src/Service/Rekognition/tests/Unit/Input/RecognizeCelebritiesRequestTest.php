<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\RecognizeCelebritiesRequest;
use AsyncAws\Rekognition\ValueObject\Image;
use AsyncAws\Rekognition\ValueObject\S3Object;

class RecognizeCelebritiesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new RecognizeCelebritiesRequest([
            'Image' => new Image([
                'Bytes' => 'Y2hhbmdlIG1l',
            ]),
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_RecognizeCelebrities.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: RekognitionService.RecognizeCelebrities

            {
            "Image": {
                "Bytes": "WTJoaGJtZGxJRzFs"
            }
        }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());

        $input = new RecognizeCelebritiesRequest([
            'Image' => new Image([
                'S3Object' => new S3Object([
                    'Bucket' => 'my_bucket_identifier',
                    'Name' => 'my_bucket_name',
                    'Version' => '5.0',
                ]),
            ]),
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_RecognizeCelebrities.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: RekognitionService.RecognizeCelebrities

            {
            "Image": {
                "S3Object": {
                    "Bucket": "my_bucket_identifier",
                    "Name": "my_bucket_name",
                    "Version": "5.0"
                }
            }
        }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
