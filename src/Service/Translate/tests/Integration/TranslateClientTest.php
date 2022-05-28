<?php

namespace AsyncAws\Translate\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Translate\Input\TranslateTextRequest;
use AsyncAws\Translate\TranslateClient;
use AsyncAws\Translate\ValueObject\TranslationSettings;

class TranslateClientTest extends TestCase
{
    public function testTranslateText(): void
    {
        self::markTestIncomplete('Cannot test without docker support for translate.');

        $client = $this->getClient();

        $input = new TranslateTextRequest([
            'Text' => 'Jag gillar glass',
            'SourceLanguageCode' => 'sv',
            'TargetLanguageCode' => 'en',
            'Settings' => new TranslationSettings([
                'Formality' => 'INFORMAL', // 'FORMAL'
                'Profanity' => 'MASK',
            ]),
        ]);
        $result = $client->translateText($input);

        $result->resolve();

        self::assertSame('I like ice cream', $result->getTranslatedText());
    }

    private function getClient(): TranslateClient
    {
        self::fail('Not implemented');

        return new TranslateClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
