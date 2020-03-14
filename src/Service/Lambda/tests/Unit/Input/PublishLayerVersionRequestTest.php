<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\LayerVersionContentInput;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;

/**
 * @see https://docs.aws.amazon.com/lambda/latest/dg/API_PublishLayerVersion.html
 */
class PublishLayerVersionRequestTest extends TestCase
{
    /**
     * @var PublishLayerVersionRequest
     */
    private $input;

    public function setUp(): void
    {
        $this->input = new PublishLayerVersionRequest([
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

        parent::setUp();
    }

    public function testRequestBody(): void
    {
        // see https://docs.aws.amazon.com/lambda/latest/dg/API_PublishLayerVersion.html
        $expected = '{
            "Description": "small description",
            "Content": {
                "S3Bucket": "myBucket",
                "S3Key": "path/to/file",
                "S3ObjectVersion": "123",
                "ZipFile": "YmluYXJ5IGNvbnRlbnQ="
            },
            "CompatibleRuntimes": ["nodejs10.x", "nodejs12.x"],
            "LicenseInfo": "MIT"
        }';

        self::assertJsonStringEqualsJsonString($expected, $this->input->request()->getBody()->stringify());
    }

    public function testRequestUrl(): void
    {
        // see https://docs.aws.amazon.com/lambda/latest/dg/API_ListLayerVersions.html
        $expected = '/2018-10-31/layers/demo/versions';

        self::assertSame($expected, $this->input->request()->getUri());
    }
}
