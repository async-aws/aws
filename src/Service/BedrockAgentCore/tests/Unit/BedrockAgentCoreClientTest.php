<?php

namespace AsyncAws\BedrockAgentCore\Tests\Unit;

use AsyncAws\BedrockAgentCore\BedrockAgentCoreClient;
use AsyncAws\BedrockAgentCore\Input\InvokeAgentRuntimeRequest;
use AsyncAws\BedrockAgentCore\Result\InvokeAgentRuntimeResponse;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class BedrockAgentCoreClientTest extends TestCase
{
    public function testInvokeAgentRuntime(): void
    {
        $client = new BedrockAgentCoreClient([], new NullProvider(), new MockHttpClient());

        $input = new InvokeAgentRuntimeRequest([
            'agentRuntimeArn' => 'arn:aws:bedrock-agentcore:eu-west-1:965624758642:runtime/my-agent',
            'payload' => '{"prompt":"Write me a love poem."}',
        ]);
        $result = $client->invokeAgentRuntime($input);

        self::assertInstanceOf(InvokeAgentRuntimeResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
