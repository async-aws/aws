<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetObjectTaggingRequest;

/**
 * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectTagging.html
 */
class GetObjectTaggingRequestTest extends TestCase
{
    public function testMinimalRequiredRequest(): void
    {
        $input = new GetObjectTaggingRequest([
            'Bucket' => 'examplebucket',
            'Key' => 'baz/HappyFace.jpg',
        ]);

        $expected = '
GET /examplebucket/baz/HappyFace.jpg?tagging HTTP/1.0
Content-Type: application/xml
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testFullRequest(): void
    {
        $input = new GetObjectTaggingRequest([
            'Bucket' => 'examplebucket',
            'Key' => 'baz/HappyFace.jpg',
            'VersionId' => 'ydlaNkwWm0SfKJR.T1b1fIdPRbldTYRI',
            'ExpectedBucketOwner' => 'expected-owner',
            'RequestPayer' => 'requester',
        ]);

        $expected = '
GET /examplebucket/baz/HappyFace.jpg?tagging&versionId=ydlaNkwWm0SfKJR.T1b1fIdPRbldTYRI HTTP/1.0
Content-Type: application/xml
x-amz-expected-bucket-owner: expected-owner
x-amz-request-payer: requester
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
