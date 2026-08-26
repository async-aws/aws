<?php

namespace AsyncAws\BedrockAgentCore\Tests\Unit\Input;

use AsyncAws\BedrockAgentCore\Input\InvokeAgentRuntimeRequest;
use AsyncAws\Core\Test\TestCase;

class InvokeAgentRuntimeRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new InvokeAgentRuntimeRequest([
            'contentType' => 'application/json',
            'accept' => 'application/json',
            'runtimeSessionId' => 'ba8dc2f0-3b3f-4f4a-9a1e-9c0d3f2b7a11',
            'runtimeUserId' => 'user-1234',
            'traceId' => 'Root=1-67891233-abcdef012345678912345678',
            'agentRuntimeArn' => 'arn:aws:bedrock-agentcore:eu-west-1:965624758642:runtime/my-agent',
            'qualifier' => 'DEFAULT',
            'payload' => '{"prompt":"Write me a love poem."}',
        ]);

        // see https://docs.aws.amazon.com/bedrock-agentcore/latest/APIReference/API_InvokeAgentRuntime.html
        $expected = '
            POST /runtimes/arn%3Aaws%3Abedrock-agentcore%3Aeu-west-1%3A965624758642%3Aruntime%2Fmy-agent/invocations?qualifier=DEFAULT HTTP/1.0
            Content-Type: application/json
            Accept: application/json
            X-Amzn-Bedrock-AgentCore-Runtime-Session-Id: ba8dc2f0-3b3f-4f4a-9a1e-9c0d3f2b7a11
            X-Amzn-Bedrock-AgentCore-Runtime-User-Id: user-1234
            X-Amzn-Trace-Id: Root=1-67891233-abcdef012345678912345678

            {"prompt":"Write me a love poem."}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testRequestWithMcpHeaders(): void
    {
        $input = new InvokeAgentRuntimeRequest([
            'mcpSessionId' => '7d3c7f1e-6c3c-4d19-9a6f-1f2b3c4d5e6f',
            'mcpProtocolVersion' => '2025-06-18',
            'mcpMethod' => 'tools/call',
            'mcpName' => 'search',
            'agentRuntimeArn' => 'arn:aws:bedrock-agentcore:eu-west-1:965624758642:runtime/my-agent',
            'payload' => '{"jsonrpc":"2.0","id":1,"method":"tools/call"}',
        ]);

        // see https://docs.aws.amazon.com/bedrock-agentcore/latest/APIReference/API_InvokeAgentRuntime.html
        $expected = '
            POST /runtimes/arn%3Aaws%3Abedrock-agentcore%3Aeu-west-1%3A965624758642%3Aruntime%2Fmy-agent/invocations HTTP/1.0
            Content-Type: application/json
            Accept: application/json
            Mcp-Session-Id: 7d3c7f1e-6c3c-4d19-9a6f-1f2b3c4d5e6f
            Mcp-Protocol-Version: 2025-06-18
            Mcp-Method: tools/call
            Mcp-Name: search

            {"jsonrpc":"2.0","id":1,"method":"tools/call"}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
