<?php

namespace AsyncAws\Ses\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Result\SendEmailResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class SendEmailResponseTest extends TestCase
{
    public function testSendEmailResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "MessageId": "EXAMPLE78603177f-7a5433e7-8edb-42ae-af10-f0181f34d6ee-000000"
        }');

        $client = new MockHttpClient($response);
        $result = new SendEmailResponse($client->request('POST', 'http://localhost'), $client);

        self::assertSame('EXAMPLE78603177f-7a5433e7-8edb-42ae-af10-f0181f34d6ee-000000', $result->getMessageId());
    }
}
