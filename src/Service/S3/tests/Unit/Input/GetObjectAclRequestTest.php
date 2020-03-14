<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetObjectAclRequest;

class GetObjectAclRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectAcl.html#API_GetObjectAcl_Examples
     */
    public function testRequest(): void
    {
        $input = new GetObjectAclRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'foo.jpg',
            'VersionId' => 'abc123',
        ]);

        $expected = '
            GET /my-bucket/foo.jpg?acl&versionId=abc123 HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
