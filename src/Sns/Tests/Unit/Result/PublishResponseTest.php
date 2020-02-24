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

        $client = new MockHttpClient($response);
        $result = new PublishResponse($client->request('POST', 'http://localhost'), $client);

        self::assertStringContainsString('change it', $result->getMessageId());
    }
}
