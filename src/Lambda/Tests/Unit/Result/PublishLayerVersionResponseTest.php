<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Lambda\Result\PublishLayerVersionResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class PublishLayerVersionResponseTest extends TestCase
{
    public function testPublishLayerVersionResponse(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('{"change": "it"}');

        $result = new PublishLayerVersionResponse($response, new MockHttpClient());

        // self::assertTODO(expected, $result->getContent());
        self::assertStringContainsString('change it', $result->getLayerArn());
        self::assertStringContainsString('change it', $result->getLayerVersionArn());
        self::assertStringContainsString('change it', $result->getDescription());
        self::assertStringContainsString('change it', $result->getCreatedDate());
        self::assertSame(1337, $result->getVersion());
        // self::assertTODO(expected, $result->getCompatibleRuntimes());
        self::assertStringContainsString('change it', $result->getLicenseInfo());
    }
}
