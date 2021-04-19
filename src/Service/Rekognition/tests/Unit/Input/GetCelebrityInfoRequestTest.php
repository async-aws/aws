<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\GetCelebrityInfoRequest;

class GetCelebrityInfoRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetCelebrityInfoRequest([
            'Id' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_GetCelebrityInfo.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
