<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\DeleteObjectTaggingRequest;

/**
 * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObjectTagging.html
 */
class DeleteObjectTaggingRequestTest extends TestCase
{
    public function testMinimalRequiredRequest(): void
    {
        $input = new DeleteObjectTaggingRequest([
            'Bucket' => 'examplebucket',
            'Key' => 'baz/HappyFace.jpg',
        ]);

        $expected = '
DELETE /examplebucket/baz/HappyFace.jpg?tagging HTTP/1.0
Content-Type: application/xml
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testRequestWithVersionId(): void
    {
        $input = new DeleteObjectTaggingRequest([
            'Bucket' => 'examplebucket',
            'Key' => 'baz/HappyFace.jpg',
            'VersionId' => 'ydlaNkwWm0SfKJR.T1b1fIdPRbldTYRI',
        ]);

        $expected = '
DELETE /examplebucket/baz/HappyFace.jpg?tagging&versionId=ydlaNkwWm0SfKJR.T1b1fIdPRbldTYRI HTTP/1.0
Content-Type: application/xml
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testFullRequest(): void
    {
        $input = new DeleteObjectTaggingRequest([
            'Bucket' => 'examplebucket',
            'Key' => 'baz/HappyFace.jpg',
            'VersionId' => 'ydlaNkwWm0SfKJR.T1b1fIdPRbldTYRI',
            'ExpectedBucketOwner' => 'expected-owner',
        ]);

        $expected = '
DELETE /examplebucket/baz/HappyFace.jpg?tagging&versionId=ydlaNkwWm0SfKJR.T1b1fIdPRbldTYRI HTTP/1.0
Content-Type: application/xml
x-amz-expected-bucket-owner: expected-owner
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
