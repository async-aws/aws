<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Result\PublishLayerVersionResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class PublishLayerVersionResponseTest extends TestCase
{
    public function testPublishLayerVersionResponse(): void
    {
        self::markTestIncomplete('Not implemented');

        // see https://docs.aws.amazon.com/SERVICE/latest/APIReference/API_METHOD.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new PublishLayerVersionResponse($client->request('POST', 'http://localhost'), $client);

        // self::assertTODO(expected, $result->getContent());
        self::assertSame('changeIt', $result->getLayerArn());
        self::assertSame('changeIt', $result->getLayerVersionArn());
        self::assertSame('changeIt', $result->getDescription());
        self::assertSame('changeIt', $result->getCreatedDate());
        self::assertSame(1337, $result->getVersion());
        // self::assertTODO(expected, $result->getCompatibleRuntimes());
        self::assertSame('changeIt', $result->getLicenseInfo());
    }
}
