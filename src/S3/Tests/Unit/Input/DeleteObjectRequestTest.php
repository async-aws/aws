<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\DeleteObjectRequest;

class DeleteObjectRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new DeleteObjectRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',
            'MFA' => 'change me',
            'VersionId' => 'change me',
            'RequestPayer' => 'change me',
            'BypassGovernanceRetention' => false,
        ]);

        // see example-1.json from SDK
        $expected = '<Bucket>ExampleBucket</Bucket>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }

    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObject.html#API_DeleteObject_Examples
     */
    public function testSimpleCase(): void
    {
        $version = 'UIORUnfndfiufdisojhr398493jfdkjFJjkndnqUifhnw89493jJFJ';
        $input = new DeleteObjectRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'my-second-image.jpg',
            'VersionId' => $version,
        ]);

        self::assertEquals('/my-bucket/my-second-image.jpg', $input->requestUri());
        self::assertEmpty($input->requestBody());

        $query = $input->requestQuery();
        self::arrayHasKey('versionId', $query);
        self::assertEquals($version, $query['versionId']);
    }
}
