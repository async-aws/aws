<?php

namespace AsyncAws\Core\Tests\Unit\Result;

use AsyncAws\Core\Sts\Result\GetCallerIdentityResponse;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class GetCallerIdentityResponseTest extends TestCase
{
    public function testGetCallerIdentityResponse(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $result = new GetCallerIdentityResponse($response, new MockHttpClient());

        self::assertStringContainsString('change it', $result->getUserId());
        self::assertStringContainsString('change it', $result->getAccount());
        self::assertStringContainsString('change it', $result->getArn());
    }
}
