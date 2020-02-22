<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Lambda\Result\InvocationResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class InvocationResponseTest extends TestCase
{
    public function testInvocationResponse(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('{"change": "it"}');

        $result = new InvocationResponse($response, new MockHttpClient());

        self::assertSame(1337, $result->getStatusCode());
        self::assertStringContainsString('change it', $result->getFunctionError());
        self::assertStringContainsString('change it', $result->getLogResult());
        // self::assertTODO(expected, $result->getPayload());
        self::assertStringContainsString('change it', $result->getExecutedVersion());
    }
}
