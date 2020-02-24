<?php

namespace AsyncAws\Ses\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Ses\Result\SendEmailResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class SendEmailResponseTest extends TestCase
{
    public function testSendEmailResponse(): void
    {
        $response = new SimpleMockedResponse('{"MessageId": "abcdef"}');

        $client = new MockHttpClient($response);
        $result = new SendEmailResponse($client->request('POST', 'http://localhost'), $client);

        self::assertStringContainsString('abcdef', $result->getMessageId());
    }
}
