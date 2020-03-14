<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\DeleteObjectRequest;

class DeleteObjectRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObject.html#API_DeleteObject_Examples
     */
    public function testRequest(): void
    {
        $version = 'UIORUnfndfiufdisojhr398493jfdkjFJjkndnqUifhnw89493jJFJ';
        $input = new DeleteObjectRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'my-second-image.jpg',
            'VersionId' => $version,
        ]);

        $expected = '
            DELETE /my-bucket/my-second-image.jpg?versionId=UIORUnfndfiufdisojhr398493jfdkjFJjkndnqUifhnw89493jJFJ HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
