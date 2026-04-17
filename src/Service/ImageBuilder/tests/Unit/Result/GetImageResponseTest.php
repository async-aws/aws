<?php

namespace AsyncAws\ImageBuilder\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\Enum\ImageStatus;
use AsyncAws\ImageBuilder\Result\GetImageResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetImageResponseTest extends TestCase
{
    public function testGetImageResponse(): void
    {
        // see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_GetImage.html
        $response = new SimpleMockedResponse('{
    "requestId": "abc-123",
    "image": {
        "arn": "arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1",
        "name": "example",
        "version": "1.0.0",
        "platform": "Linux",
        "state": {
            "status": "AVAILABLE",
            "reason": ""
        },
        "outputResources": {
            "amis": [
                {
                    "region": "us-east-1",
                    "image": "ami-0aaaaaaaaaaaaaaa1",
                    "name": "example 1.0.0",
                    "accountId": "123456789012"
                }
            ]
        }
    }
}');

        $client = new MockHttpClient($response);
        $result = new GetImageResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('abc-123', $result->getRequestId());

        $image = $result->getImage();
        self::assertNotNull($image);
        self::assertSame('arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1', $image->getArn());
        self::assertSame(ImageStatus::AVAILABLE, $image->getState()->getStatus());
        self::assertSame('', $image->getState()->getReason());

        $amis = $image->getOutputResources()->getAmis();
        self::assertCount(1, $amis);
        self::assertSame('ami-0aaaaaaaaaaaaaaa1', $amis[0]->getImage());
        self::assertSame('us-east-1', $amis[0]->getRegion());
    }
}
