<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\GetSessionStatusResponse;
use AsyncAws\Athena\ValueObject\SessionStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetSessionStatusResponseTest extends TestCase
{
    public function testGetSessionStatusResponse(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetSessionStatus.html
        $response = new SimpleMockedResponse('{
           "SessionId": "session-iad-1256",
           "Status": {
              "EndDateTime": 1680596075.353,
              "IdleSinceDateTime": 1680596075.052,
              "LastModifiedDateTime": 1680596075.0032,
              "StartDateTime": 1680596075,
              "State": "CREATING",
              "StateChangeReason": "insertion"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new GetSessionStatusResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('session-iad-1256', $result->getSessionId());
        self::assertInstanceOf(SessionStatus::class, $result->getStatus());
        self::assertSame('CREATING', $result->getStatus()->getState());
    }
}
