<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Result;

use AsyncAws\CodeCommit\Result\PutRepositoryTriggersOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutRepositoryTriggersOutputTest extends TestCase
{
    public function testPutRepositoryTriggersOutput(): void
    {
        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_PutRepositoryTriggers.html
        $response = new SimpleMockedResponse('{
    "configurationId": "6fa51cd8-35c1-EXAMPLE"
}');

        $client = new MockHttpClient($response);
        $result = new PutRepositoryTriggersOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('6fa51cd8-35c1-EXAMPLE', $result->getconfigurationId());
    }
}
