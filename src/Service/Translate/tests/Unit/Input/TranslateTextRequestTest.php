<?php

namespace AsyncAws\Translate\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Translate\Input\TranslateTextRequest;
use AsyncAws\Translate\ValueObject\TranslationSettings;

class TranslateTextRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new TranslateTextRequest([
            'Text' => 'Jag gillar glass',
            'TerminologyNames' => ['my-list'],
            'SourceLanguageCode' => 'sv',
            'TargetLanguageCode' => 'en',
            'Settings' => new TranslationSettings([
                'Formality' => 'INFORMAL',
                'Profanity' => 'MASK',
            ]),
        ]);

        // see https://docs.aws.amazon.com/translate/latest/dg/API_Reference.html/API_TranslateText.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AWSShineFrontendService_20170701.TranslateText
            Accept: application/json


            {
                "Settings": {
                     "Formality": "INFORMAL",
                     "Profanity": "MASK"
                },
               "SourceLanguageCode": "sv",
               "TargetLanguageCode": "en",
               "TerminologyNames": [
                   "my-list"
               ],
               "Text": "Jag gillar glass"
        }
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
