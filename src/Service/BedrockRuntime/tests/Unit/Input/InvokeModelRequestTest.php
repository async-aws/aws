<?php

namespace AsyncAws\BedrockRuntime\Tests\Unit\Input;

use AsyncAws\BedrockRuntime\Input\InvokeModelRequest;
use AsyncAws\Core\Test\TestCase;

class InvokeModelRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new InvokeModelRequest([
            'body' => '{"anthropic_version":"bedrock-2023-05-31","max_tokens":4096,"messages":[{"role":"user","content":[{"type":"text","text":"Write me a love poem."}]}],"temperature":1}',
            'contentType' => 'application/json',
            'accept' => 'application/json',
            'modelId' => 'us.anthropic.claude-3-7-sonnet-20250219-v1:0',
            'trace' => 'DISABLED',
            'guardrailIdentifier' => 'arn:aws:bedrock:eu-west-1:965624758642:guardrail/azertyuiopqs',
            'guardrailVersion' => 'DRAFT',
            'performanceConfigLatency' => 'standard',
        ]);

        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_InvokeModel.html
        $expected = '
            POST /model/us.anthropic.claude-3-7-sonnet-20250219-v1%3A0/invoke HTTP/1.0
            Content-Type: application/json
            Accept: application/json
            X-Amzn-Bedrock-GuardrailIdentifier: arn:aws:bedrock:eu-west-1:965624758642:guardrail/azertyuiopqs
            X-Amzn-Bedrock-GuardrailVersion: DRAFT
            X-Amzn-Bedrock-PerformanceConfig-Latency: standard
            X-Amzn-Bedrock-Trace: DISABLED

            {"anthropic_version":"bedrock-2023-05-31","max_tokens":4096,"messages":[{"role":"user","content":[{"type":"text","text":"Write me a love poem."}]}],"temperature":1}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
