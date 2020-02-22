<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Lambda\Result\ListLayerVersionsResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class ListLayerVersionsResponseTest extends TestCase
{
    public function testListLayerVersionsResponse(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('{"change": "it"}');

        $result = new ListLayerVersionsResponse($response, new MockHttpClient());

        self::assertStringContainsString('change it', $result->getNextMarker());
        // self::assertTODO(expected, $result->getLayerVersions());
    }
}
