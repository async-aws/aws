<?php

namespace AsyncAws\BedrockAgentCore\Tests\Unit\Result;

use AsyncAws\BedrockAgentCore\Result\InvokeAgentRuntimeResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class InvokeAgentRuntimeResponseTest extends TestCase
{
    public function testInvokeAgentRuntimeResponse(): void
    {
        // see https://docs.aws.amazon.com/bedrock-agentcore/latest/APIReference/API_InvokeAgentRuntime.html
        $response = new SimpleMockedResponse('{"result":"Roses are red."}', [
            'Content-Type' => 'application/json',
            'X-Amzn-Bedrock-AgentCore-Runtime-Session-Id' => 'ba8dc2f0-3b3f-4f4a-9a1e-9c0d3f2b7a11',
            'Mcp-Session-Id' => '7d3c7f1e-6c3c-4d19-9a6f-1f2b3c4d5e6f',
            'Mcp-Protocol-Version' => '2025-06-18',
            'X-Amzn-Trace-Id' => 'Root=1-67891233-abcdef012345678912345678',
            'traceparent' => '00-0af7651916cd43dd8448eb211c80319c-b7ad6b7169203331-01',
            'tracestate' => 'congo=t61rcWkgMzE',
            'baggage' => 'userId=alice',
        ]);

        $client = new MockHttpClient($response);
        $result = new InvokeAgentRuntimeResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('ba8dc2f0-3b3f-4f4a-9a1e-9c0d3f2b7a11', $result->getRuntimeSessionId());
        self::assertSame('7d3c7f1e-6c3c-4d19-9a6f-1f2b3c4d5e6f', $result->getMcpSessionId());
        self::assertSame('2025-06-18', $result->getMcpProtocolVersion());
        self::assertSame('Root=1-67891233-abcdef012345678912345678', $result->getTraceId());
        self::assertSame('00-0af7651916cd43dd8448eb211c80319c-b7ad6b7169203331-01', $result->getTraceParent());
        self::assertSame('congo=t61rcWkgMzE', $result->getTraceState());
        self::assertSame('userId=alice', $result->getBaggage());
        self::assertSame('application/json', $result->getContentType());
        self::assertSame('{"result":"Roses are red."}', $result->getResponse());
        self::assertSame(200, $result->getStatusCode());
    }

    public function testInvokeAgentRuntimeResponseWithoutOptionalHeaders(): void
    {
        $response = new SimpleMockedResponse('data: {"delta":"Roses"}', [
            'Content-Type' => 'text/event-stream',
        ]);

        $client = new MockHttpClient($response);
        $result = new InvokeAgentRuntimeResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('text/event-stream', $result->getContentType());
        self::assertSame('data: {"delta":"Roses"}', $result->getResponse());
        self::assertNull($result->getRuntimeSessionId());
        self::assertNull($result->getMcpSessionId());
        self::assertNull($result->getTraceId());
    }
}
