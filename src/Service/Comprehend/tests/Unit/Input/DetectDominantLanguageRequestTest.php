<?php

namespace AsyncAws\Comprehend\Tests\Unit\Input;

use AsyncAws\Comprehend\Input\DetectDominantLanguageRequest;
use AsyncAws\Core\Test\TestCase;

class DetectDominantLanguageRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new DetectDominantLanguageRequest([
            'Text' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/comprehend/latest/dg/API_DetectDominantLanguage.html
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
