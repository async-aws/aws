<?php

namespace AsyncAws\Comprehend\Tests\Unit\Result;

use AsyncAws\Comprehend\Result\DetectDominantLanguageResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DetectDominantLanguageResponseTest extends TestCase
{
    public function testDetectDominantLanguageResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/comprehend/latest/APIReference/API_DetectDominantLanguage.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new DetectDominantLanguageResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        // self::assertTODO(expected, $result->getLanguages());
    }
}
