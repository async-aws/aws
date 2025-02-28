<?php

namespace AsyncAws\BedrockRuntime\Tests\Unit\Result;

use AsyncAws\BedrockRuntime\Result\InvokeModelResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class InvokeModelResponseTest extends TestCase
{
    public function testInvokeModelResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_InvokeModel.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new InvokeModelResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        // self::assertTODO(expected, $result->getBody());
        self::assertSame('changeIt', $result->getContentType());
        self::assertSame('changeIt', $result->getPerformanceConfigLatency());
    }
}
