<?php

namespace AsyncAws\Comprehend\Tests\Unit;

use AsyncAws\Comprehend\Input\DetectDominantLanguageRequest;
use AsyncAws\Comprehend\Result\DetectDominantLanguageResponse;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class ComprehendClientTest extends TestCase
{
    public function testDetectDominantLanguage(): void
    {
        $client = new ComprehendClient([], new NullProvider(), new MockHttpClient());

        $input = new DetectDominantLanguageRequest([
            'Text' => 'change me',
        ]);
        $result = $client->detectDominantLanguage($input);

        self::assertInstanceOf(DetectDominantLanguageResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
