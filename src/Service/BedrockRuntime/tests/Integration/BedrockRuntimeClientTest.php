<?php

namespace AsyncAws\BedrockRuntime\Tests\Integration;

use AsyncAws\BedrockRuntime\BedrockRuntimeClient;
use AsyncAws\BedrockRuntime\Input\InvokeModelRequest;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class BedrockRuntimeClientTest extends TestCase
{
    public function testInvokeModel(): void
    {
        $client = $this->getClient();

        $input = new InvokeModelRequest([
            'body' => 'change me',
            'contentType' => 'change me',
            'accept' => 'change me',
            'modelId' => 'change me',
            'trace' => 'change me',
            'guardrailIdentifier' => 'change me',
            'guardrailVersion' => 'change me',
            'performanceConfigLatency' => 'change me',
        ]);
        $result = $client->invokeModel($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getBody());
        self::assertSame('changeIt', $result->getContentType());
        self::assertSame('changeIt', $result->getPerformanceConfigLatency());
    }

    private function getClient(): BedrockRuntimeClient
    {
        self::markTestSkipped('There is no docker image available for BedrockRuntime.');

        return new BedrockRuntimeClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
