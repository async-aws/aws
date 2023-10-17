<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\UploadPartCopyRequest;

class UploadPartCopyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new UploadPartCopyRequest([
            'Bucket' => 'change me',
            'CopySource' => 'change me',
            'CopySourceIfMatch' => 'change me',
            'CopySourceIfModifiedSince' => new \DateTimeImmutable(),
            'CopySourceIfNoneMatch' => 'change me',
            'CopySourceIfUnmodifiedSince' => new \DateTimeImmutable(),
            'CopySourceRange' => 'change me',
            'Key' => 'change me',
            'PartNumber' => 1337,
            'UploadId' => 'change me',
            'SSECustomerAlgorithm' => 'change me',
            'SSECustomerKey' => 'change me',
            'SSECustomerKeyMD5' => 'change me',
            'CopySourceSSECustomerAlgorithm' => 'change me',
            'CopySourceSSECustomerKey' => 'change me',
            'CopySourceSSECustomerKeyMD5' => 'change me',
            'RequestPayer' => 'change me',
            'ExpectedBucketOwner' => 'change me',
            'ExpectedSourceBucketOwner' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
            PUT / HTTP/1.0
            Content-Type: application/xml

            <Bucket>examplebucket</Bucket>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
