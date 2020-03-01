<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetObjectAclRequest;

class GetObjectAclRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectAcl.html#API_GetObjectAcl_Examples
     */
    public function testRequestBody(): void
    {
        $input = new GetObjectAclRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'foo.jpg',
            'VersionId' => 'abc123',
        ]);

        self::assertEquals('/my-bucket/foo.jpg?acl', $input->requestUri());
        self::assertEmpty($input->requestBody());

        $query = $input->requestQuery();
        self::assertArrayHasKey('versionId', $query);
        self::assertEquals('abc123', $query['versionId']);
    }
}
