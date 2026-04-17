<?php

namespace AsyncAws\ImageBuilder\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\Result\DeleteImageResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteImageResponseTest extends TestCase
{
    public function testDeleteImageResponse(): void
    {
        // see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_DeleteImage.html
        $response = new SimpleMockedResponse('{
    "requestId": "abc-123",
    "imageBuildVersionArn": "arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1"
}');

        $client = new MockHttpClient($response);
        $result = new DeleteImageResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('abc-123', $result->getRequestId());
        self::assertSame('arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1', $result->getImageBuildVersionArn());
    }
}
