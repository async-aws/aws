<?php

namespace AsyncAws\BedrockRuntime\Tests\Unit\Input;

use AsyncAws\BedrockRuntime\Input\InvokeModelRequest;
use AsyncAws\Core\Test\TestCase;

class InvokeModelRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

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

        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_Operations_Amazon_Bedrock_Runtime.html/API_InvokeModel.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
