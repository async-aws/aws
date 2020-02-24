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

        $client = new MockHttpClient($response);
        $result = new AddLayerVersionPermissionResponse($client->request('POST', 'http://localhost'), $client);

        self::assertStringContainsString('change it', $result->getStatement());
        self::assertStringContainsString('change it', $result->getRevisionId());
    }
}
