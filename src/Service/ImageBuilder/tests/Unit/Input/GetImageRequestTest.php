<?php

namespace AsyncAws\ImageBuilder\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\Input\GetImageRequest;

class GetImageRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetImageRequest([
            'imageBuildVersionArn' => 'arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1',
        ]);

        // GetImage is GET /GetImage with imageBuildVersionArn on the query string.
        // see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_GetImage.html
        $expected = '
            GET /GetImage?imageBuildVersionArn=arn%3Aaws%3Aimagebuilder%3Aus-east-1%3A123456789012%3Aimage%2Fexample%2F1.0.0%2F1 HTTP/1.0
            Accept: application/json
            Content-Type: application/json

        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
