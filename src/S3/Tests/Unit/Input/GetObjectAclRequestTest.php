<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetObjectAclRequest;

class GetObjectAclRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new GetObjectAclRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',
            'VersionId' => 'change me',
            'RequestPayer' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '<Bucket>examplebucket</Bucket>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }

    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectAcl.html#API_GetObjectAcl_Examples
     */
    public function testSimpleCase(): void
    {
        $input = new GetObjectAclRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'foo.jpg',
            'VersionId' => 'abc123',
        ]);

        self::assertEquals('/my-bucket/foo.jpg?acl', $input->requestUri());
        self::assertEmpty($input->requestBody());

        $query = $input->requestQuery();
        self::arrayHasKey('versionId', $query);
        self::assertEquals('abc123', $query['versionId']);
    }
}
