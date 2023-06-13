<?php

namespace AsyncAws\Translate\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Translate\Input\TranslateTextRequest;
use AsyncAws\Translate\Result\TranslateTextResponse;
use AsyncAws\Translate\TranslateClient;
use Symfony\Component\HttpClient\MockHttpClient;

class TranslateClientTest extends TestCase
{
    public function testTranslateText(): void
    {
        $client = new TranslateClient([], new NullProvider(), new MockHttpClient());

        $input = new TranslateTextRequest([
            'Text' => 'Jag gillar glass',

            'SourceLanguageCode' => 'sv',
            'TargetLanguageCode' => 'en',
        ]);
        $result = $client->translateText($input);

        self::assertInstanceOf(TranslateTextResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
