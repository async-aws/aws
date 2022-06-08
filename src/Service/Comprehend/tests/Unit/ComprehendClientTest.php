<?php

namespace AsyncAws\Comprehend\Tests\Unit;

use AsyncAws\Comprehend\ComprehendClient;
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
            'Text' => 'This is an example text',
        ]);
        $result = $client->detectDominantLanguage($input);

        self::assertInstanceOf(DetectDominantLanguageResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
