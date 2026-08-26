<?php

namespace AsyncAws\BedrockAgentCore\Tests\Integration;

use AsyncAws\BedrockAgentCore\BedrockAgentCoreClient;
use AsyncAws\BedrockAgentCore\Input\InvokeAgentRuntimeRequest;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class BedrockAgentCoreClientTest extends TestCase
{
    public function testInvokeAgentRuntime(): void
    {
        $client = $this->getClient();

        $input = new InvokeAgentRuntimeRequest([
            'contentType' => 'application/json',
            'accept' => 'application/json',
            'agentRuntimeArn' => 'arn:aws:bedrock-agentcore:eu-west-1:965624758642:runtime/my-agent',
            'qualifier' => 'DEFAULT',
            'payload' => '{"prompt":"Write me a love poem."}',
        ]);
        $result = $client->invokeAgentRuntime($input);

        $result->resolve();

        self::assertSame('application/json', $result->getContentType());
        self::assertNotNull($result->getResponse());
        self::assertSame(200, $result->getStatusCode());
    }

    private function getClient(): BedrockAgentCoreClient
    {
        self::markTestSkipped('There is no docker image available for BedrockAgentCore.');

        return new BedrockAgentCoreClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
