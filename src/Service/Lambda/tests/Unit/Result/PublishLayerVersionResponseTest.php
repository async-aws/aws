<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Result\PublishLayerVersionResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PublishLayerVersionResponseTest extends TestCase
{
    public function testPublishLayerVersionResponse(): void
    {
        // see https://docs.aws.amazon.com/lambda/latest/dg/API_PublishLayerVersion.html
        $response = new SimpleMockedResponse('{
            "CompatibleRuntimes": ["nodejs10.x", "nodejs12.x"],
            "Content": {
                "CodeSha256": "456789abcdf",
                "CodeSize": 145,
                "Location": "http://s3.amazon/bucket/path"
            },
            "CreatedDate": "1997-07-16T19:20:30+01:00",
            "Description": "demo",
            "LayerArn": "arn:::fn:arn",
            "LayerVersionArn": "arn:::version:arn",
            "LicenseInfo": "MIT",
            "Version": 4
            }
        ');

        $client = new MockHttpClient($response);
        $result = new PublishLayerVersionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:::fn:arn', $result->getLayerArn());
        self::assertSame('arn:::version:arn', $result->getLayerVersionArn());
        self::assertSame('demo', $result->getDescription());
        self::assertSame('1997-07-16T19:20:30+01:00', $result->getCreatedDate());
        self::assertSame(4, $result->getVersion());
        self::assertSame(['nodejs10.x', 'nodejs12.x'], $result->getCompatibleRuntimes());
        self::assertSame('MIT', $result->getLicenseInfo());
    }
}
