<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\StartSessionResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StartSessionResponseTest extends TestCase
{
    public function testStartSessionResponse(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_StartSession.html
        $response = new SimpleMockedResponse('{
           "SessionId": "iad-session-12540",
           "State": "IDLE"
        }');

        $client = new MockHttpClient($response);
        $result = new StartSessionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('iad-session-12540', $result->getSessionId());
        self::assertSame('IDLE', $result->getState());
    }
}
