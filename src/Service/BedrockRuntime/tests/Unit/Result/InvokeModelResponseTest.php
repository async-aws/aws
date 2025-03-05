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
        // see https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_InvokeModel.html
        $response = new SimpleMockedResponse(
            '{"id":"msg_bdrk_01SK3CsA23VBbZig4HqemAoo","type":"message","role":"assistant","model":"claude-3-7-sonnet-20250219","content":[{"type":"text","text":"# Whispers of the Heart\n\nIn the quiet moments between breaths,\nI find my thoughts wandering to you,\nLike stars that navigate sailors home,\nYour smile guides me through.\n\nThe universe conspired in silent ways,\nTo weave our paths into one,\nNow each sunrise holds new promise,\nA dance we\'ve just begun.\n\nYour laughter echoes in my dreams,\nA melody I\'ve known all along,\nMy heart recognizes yours completely,\nLike remembering a forgotten song.\n\nI love you not just for who you are,\nBut for who I become when we\'re together,\nA better version, a truer self,\nA love to cherish forever."}],"stop_reason":"end_turn","stop_sequence":null,"usage":{"input_tokens":13,"cache_creation_input_tokens":0,"cache_read_input_tokens":0,"output_tokens":153}}',
            ['Content-type' => 'application/json', 'X-Amzn-Bedrock-PerformanceConfig-Latency' => 'standard']
        );

        $client = new MockHttpClient($response);
        $result = new InvokeModelResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('{"id":"msg_bdrk_01SK3CsA23VBbZig4HqemAoo","type":"message","role":"assistant","model":"claude-3-7-sonnet-20250219","content":[{"type":"text","text":"# Whispers of the Heart\n\nIn the quiet moments between breaths,\nI find my thoughts wandering to you,\nLike stars that navigate sailors home,\nYour smile guides me through.\n\nThe universe conspired in silent ways,\nTo weave our paths into one,\nNow each sunrise holds new promise,\nA dance we\'ve just begun.\n\nYour laughter echoes in my dreams,\nA melody I\'ve known all along,\nMy heart recognizes yours completely,\nLike remembering a forgotten song.\n\nI love you not just for who you are,\nBut for who I become when we\'re together,\nA better version, a truer self,\nA love to cherish forever."}],"stop_reason":"end_turn","stop_sequence":null,"usage":{"input_tokens":13,"cache_creation_input_tokens":0,"cache_read_input_tokens":0,"output_tokens":153}}', $result->getBody());
        self::assertSame('application/json', $result->getContentType());
        self::assertSame('standard', $result->getPerformanceConfigLatency());
    }
}
