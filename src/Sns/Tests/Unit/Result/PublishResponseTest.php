<?php

namespace AsyncAws\Sns\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sns\Result\PublishResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class PublishResponseTest extends TestCase
{
    public function testPublishResponse(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $result = new PublishResponse($response, new MockHttpClient());

        self::assertStringContainsString('change it', $result->getMessageId());
    }
}
