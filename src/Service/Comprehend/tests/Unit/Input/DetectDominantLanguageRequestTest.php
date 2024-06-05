<?php

namespace AsyncAws\Comprehend\Tests\Unit\Input;

use AsyncAws\Comprehend\Input\DetectDominantLanguageRequest;
use AsyncAws\Core\Test\TestCase;

class DetectDominantLanguageRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DetectDominantLanguageRequest([
            'Text' => 'This is my example text',
        ]);

        // see https://docs.aws.amazon.com/comprehend/latest/dg/API_DetectDominantLanguage.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: Comprehend_20171127.DetectDominantLanguage
            Accept: application/json

            {
                "Text": "This is my example text"
            }
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
