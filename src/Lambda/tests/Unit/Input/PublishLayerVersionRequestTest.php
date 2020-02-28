<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\LayerVersionContentInput;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;

class PublishLayerVersionRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new PublishLayerVersionRequest([
            'LayerName' => 'change me',
            'Description' => 'change me',
            'Content' => new LayerVersionContentInput([
                'S3Bucket' => 'change me',
                'S3Key' => 'change me',
                'S3ObjectVersion' => 'change me',
                'ZipFile' => 'change me',
            ]),
            'CompatibleRuntimes' => ['change me'],
            'LicenseInfo' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/Lambda/latest/APIReference/API_PublishLayerVersion.html
        $expected = '{
            "change": "it"
        }';

        self::assertJsonStringEqualsJsonString($expected, $input->requestBody());
    }
}
