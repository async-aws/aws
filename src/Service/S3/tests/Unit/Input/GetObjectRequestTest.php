<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetObjectRequest;

class GetObjectRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObject.html
     */
    public function testRequest(): void
    {
        $input = new GetObjectRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'foo.jpg',
            'VersionId' => 'abc123',
        ]);

        $expected = '
            GET /my-bucket/foo.jpg?versionId=abc123 HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testRequestWithSpecialChar(): void
    {
        $input = new GetObjectRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'foo-with#pound.jpg',
            'VersionId' => 'abc123',
        ]);

        $expected = '
            GET /my-bucket/foo-with%23pound.jpg?versionId=abc123 HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testRequestDoesNotEscapeSlash(): void
    {
        $input = new GetObjectRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'foo/bar.jpg',
            'VersionId' => 'abc123',
        ]);

        $expected = '
            GET /my-bucket/foo/bar.jpg?versionId=abc123 HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
