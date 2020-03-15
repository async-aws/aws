<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;
use AsyncAws\Lambda\ValueObject\LayerVersionContentInput;

/**
 * @see https://docs.aws.amazon.com/lambda/latest/dg/API_PublishLayerVersion.html
 */
class PublishLayerVersionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PublishLayerVersionRequest([
            'LayerName' => 'demo',
            'Description' => 'small description',
            'Content' => new LayerVersionContentInput([
                'S3Bucket' => 'myBucket',
                'S3Key' => 'path/to/file',
                'S3ObjectVersion' => '123',
                'ZipFile' => 'binary content',
            ]),
            'CompatibleRuntimes' => ['nodejs10.x', 'nodejs12.x'],
            'LicenseInfo' => 'MIT',
        ]);

        // see https://docs.aws.amazon.com/lambda/latest/dg/API_PublishLayerVersion.html
        $expected = '
            POST /2018-10-31/layers/demo/versions HTTP/1.0
            Content-Type: application/json

            {
                "Description": "small description",
                "Content": {
                    "S3Bucket": "myBucket",
                    "S3Key": "path/to/file",
                    "S3ObjectVersion": "123",
                    "ZipFile": "YmluYXJ5IGNvbnRlbnQ="
                },
                "CompatibleRuntimes": ["nodejs10.x", "nodejs12.x"],
                "LicenseInfo": "MIT"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
