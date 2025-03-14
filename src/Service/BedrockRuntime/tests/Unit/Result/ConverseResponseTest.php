<?php

namespace AsyncAws\BedrockRuntime\Tests\Unit\Result;

use AsyncAws\BedrockRuntime\Result\ConverseResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ConverseResponseTest extends TestCase
{
    public function testConverseResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_Converse.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ConverseResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        // self::assertTODO(expected, $result->getOutput());
        self::assertSame('changeIt', $result->getStopReason());
        // self::assertTODO(expected, $result->getUsage());
        // self::assertTODO(expected, $result->getMetrics());
        // self::assertTODO(expected, $result->getAdditionalModelResponseFields());
        // self::assertTODO(expected, $result->getTrace());
        // self::assertTODO(expected, $result->getPerformanceConfig());
    }
}
