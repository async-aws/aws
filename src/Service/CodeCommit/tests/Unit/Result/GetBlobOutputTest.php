<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Result;

use AsyncAws\CodeCommit\Result\GetBlobOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetBlobOutputTest extends TestCase
{
    public function testGetBlobOutput(): void
    {
        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetBlob.html
        $response = new SimpleMockedResponse('{
            "content": "SGVsbG8sIHdvcmxk"
        }');

        $client = new MockHttpClient($response);
        $result = new GetBlobOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('Hello, world', $result->getcontent());
    }
}
