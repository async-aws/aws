<?php

namespace AsyncAws\Translate\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Translate\Result\TranslateTextResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class TranslateTextResponseTest extends TestCase
{
    public function testTranslateTextResponse(): void
    {
        // see https://docs.aws.amazon.com/translate/latest/APIReference/API_TranslateText.html
        $response = new SimpleMockedResponse('{
   "AppliedSettings": {
      "Profanity": "MASK"
   },
   "SourceLanguageCode": "sv",
   "TargetLanguageCode": "en",
   "TranslatedText": "I like ice cream"
}');

        $client = new MockHttpClient($response);
        $result = new TranslateTextResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('I like ice cream', $result->getTranslatedText());
        self::assertSame('sv', $result->getSourceLanguageCode());
        self::assertSame('en', $result->getTargetLanguageCode());
        self::assertSame('MASK', $result->getAppliedSettings()->getProfanity());
    }
}
