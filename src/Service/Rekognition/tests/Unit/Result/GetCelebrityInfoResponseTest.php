<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Result\GetCelebrityInfoResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetCelebrityInfoResponseTest extends TestCase
{
    public function testGetCelebrityInfoResponse(): void
    {
        // see https://docs.aws.amazon.com/rekognition/latest/APIReference/API_GetCelebrityInfo.html
        $response = new SimpleMockedResponse('{
            "Name": "Tim Berners-Lee",
            "Urls": [
                "https://fr.wikipedia.org/wiki/Tim_Berners-Lee"
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new GetCelebrityInfoResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('Tim Berners-Lee', $result->getName());
        self::assertCount(1, $result->getUrls());
    }
}
