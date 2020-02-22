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
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('{"change": "it"}');

        $result = new SendEmailResponse($response, new MockHttpClient());

        self::assertStringContainsString('change it', $result->getMessageId());
    }
}
