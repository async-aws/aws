<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Lambda\Result\AddLayerVersionPermissionResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class AddLayerVersionPermissionResponseTest extends TestCase
{
    public function testAddLayerVersionPermissionResponse(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('{"change": "it"}');

        $result = new AddLayerVersionPermissionResponse($response, new MockHttpClient());

        self::assertStringContainsString('change it', $result->getStatement());
        self::assertStringContainsString('change it', $result->getRevisionId());
    }
}
