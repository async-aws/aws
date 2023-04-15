<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\TerminateSessionResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class TerminateSessionResponseTest extends TestCase
{
    public function testTerminateSessionResponse(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_TerminateSession.html
        $response = new SimpleMockedResponse('{
           "State": "TERMINATING"
        }');

        $client = new MockHttpClient($response);
        $result = new TerminateSessionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('TERMINATING', $result->getState());
        self::assertTrue($result->info()['resolved']);
    }
}
