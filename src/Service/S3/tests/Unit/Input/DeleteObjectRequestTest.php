<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\DeleteObjectRequest;

class DeleteObjectRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObject.html#API_DeleteObject_Examples
     */
    public function testRequestBody(): void
    {
        $version = 'UIORUnfndfiufdisojhr398493jfdkjFJjkndnqUifhnw89493jJFJ';
        $input = new DeleteObjectRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'my-second-image.jpg',
            'VersionId' => $version,
        ]);

        self::assertEquals('/my-bucket/my-second-image.jpg', $input->requestUri());

        $query = $input->requestQuery();
        self::assertArrayHasKey('versionId', $query);
        self::assertEquals($version, $query['versionId']);
    }
}
