<?php

namespace AsyncAws\ImageBuilder\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\Enum\ImageType;
use AsyncAws\ImageBuilder\Enum\Platform;
use AsyncAws\ImageBuilder\ImageBuilderClient;
use AsyncAws\ImageBuilder\Input\ListImagesRequest;
use AsyncAws\ImageBuilder\Result\ListImagesResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListImagesResponseTest extends TestCase
{
    public function testListImagesResponse(): void
    {
        // see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_ListImages.html
        $response = new SimpleMockedResponse('{
    "requestId": "abc-123",
    "imageVersionList": [
        {
            "arn": "arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0",
            "name": "example",
            "type": "AMI",
            "version": "1.0.0",
            "platform": "Linux",
            "osVersion": "Amazon Linux 2",
            "owner": "123456789012",
            "dateCreated": "2024-01-01T00:00:00Z"
        }
    ],
    "nextToken": "next-page-token"
}');

        $client = new MockHttpClient($response);
        $result = new ListImagesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new ImageBuilderClient(), new ListImagesRequest([]));

        self::assertSame('abc-123', $result->getRequestId());
        self::assertSame('next-page-token', $result->getNextToken());

        $versions = iterator_to_array($result->getImageVersionList(true));
        self::assertCount(1, $versions);
        self::assertSame('arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0', $versions[0]->getArn());
        self::assertSame('example', $versions[0]->getName());
        self::assertSame(ImageType::AMI, $versions[0]->getType());
        self::assertSame('1.0.0', $versions[0]->getVersion());
        self::assertSame(Platform::LINUX, $versions[0]->getPlatform());
        self::assertSame('Amazon Linux 2', $versions[0]->getOsVersion());
        self::assertSame('123456789012', $versions[0]->getOwner());
    }
}
