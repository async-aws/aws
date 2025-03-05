<?php

namespace AsyncAws\BedrockRuntime\Tests\Unit;

use AsyncAws\BedrockRuntime\BedrockRuntimeClient;
use AsyncAws\BedrockRuntime\Input\InvokeModelRequest;
use AsyncAws\BedrockRuntime\Result\InvokeModelResponse;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class BedrockRuntimeClientTest extends TestCase
{
    public function testInvokeModel(): void
    {
        $client = new BedrockRuntimeClient([], new NullProvider(), new MockHttpClient());

        $input = new InvokeModelRequest([
            'modelId' => 'change me',
        ]);
        $result = $client->invokeModel($input);

        self::assertInstanceOf(InvokeModelResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
