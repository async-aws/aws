<?php

namespace AsyncAws\ImageBuilder\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\Enum\ImageStatus;
use AsyncAws\ImageBuilder\Enum\ImageType;
use AsyncAws\ImageBuilder\Enum\Platform;
use AsyncAws\ImageBuilder\ImageBuilderClient;
use AsyncAws\ImageBuilder\Input\ListImageBuildVersionsRequest;
use AsyncAws\ImageBuilder\Result\ListImageBuildVersionsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListImageBuildVersionsResponseTest extends TestCase
{
    public function testListImageBuildVersionsResponse(): void
    {
        // see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_ListImageBuildVersions.html
        $response = new SimpleMockedResponse('{
    "requestId": "abc-123",
    "imageSummaryList": [
        {
            "arn": "arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1",
            "name": "example",
            "type": "AMI",
            "version": "1.0.0",
            "platform": "Linux",
            "osVersion": "Amazon Linux 2",
            "state": {
                "status": "AVAILABLE",
                "reason": ""
            },
            "owner": "123456789012",
            "dateCreated": "2024-01-01T00:00:00Z"
        }
    ],
    "nextToken": "next-page-token"
}');

        $client = new MockHttpClient($response);
        $result = new ListImageBuildVersionsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new ImageBuilderClient(), new ListImageBuildVersionsRequest([]));

        self::assertSame('abc-123', $result->getRequestId());
        self::assertSame('next-page-token', $result->getNextToken());

        $summaries = iterator_to_array($result->getImageSummaryList(true));
        self::assertCount(1, $summaries);
        self::assertSame('arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1', $summaries[0]->getArn());
        self::assertSame('example', $summaries[0]->getName());
        self::assertSame(ImageType::AMI, $summaries[0]->getType());
        self::assertSame('1.0.0', $summaries[0]->getVersion());
        self::assertSame(Platform::LINUX, $summaries[0]->getPlatform());
        self::assertSame(ImageStatus::AVAILABLE, $summaries[0]->getState()->getStatus());
    }
}
