<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\GetCelebrityInfoRequest;

class GetCelebrityInfoRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetCelebrityInfoRequest([
            'Id' => '1XJ5dK1a',
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_GetCelebrityInfo.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: RekognitionService.GetCelebrityInfo

            {
            "Id": "1XJ5dK1a"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
