<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\HeadObjectRequest;

class HeadObjectRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new HeadObjectRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'foo.jpg',
            'VersionId' => 'abc123',
        ]);

        $expected = '
            HEAD /my-bucket/foo.jpg?versionId=abc123 HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
