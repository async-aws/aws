<?php

namespace AsyncAws\ImageBuilder\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\Result\StartImagePipelineExecutionResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StartImagePipelineExecutionResponseTest extends TestCase
{
    public function testStartImagePipelineExecutionResponse(): void
    {
        // see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_StartImagePipelineExecution.html
        $response = new SimpleMockedResponse('{
    "requestId": "abc-123",
    "clientToken": "test-idempotency-token",
    "imageBuildVersionArn": "arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1"
}');

        $client = new MockHttpClient($response);
        $result = new StartImagePipelineExecutionResponse(new Response($client->request('PUT', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('abc-123', $result->getRequestId());
        self::assertSame('test-idempotency-token', $result->getClientToken());
        self::assertSame('arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1', $result->getImageBuildVersionArn());
    }
}
