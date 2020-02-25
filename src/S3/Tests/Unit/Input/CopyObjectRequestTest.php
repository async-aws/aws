<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\CopyObjectRequest;

class CopyObjectRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new CopyObjectRequest([
            'ACL' => 'change me',
            'Bucket' => 'change me',
            'CacheControl' => 'change me',
            'ContentDisposition' => 'change me',
            'ContentEncoding' => 'change me',
            'ContentLanguage' => 'change me',
            'ContentType' => 'change me',
            'CopySource' => 'change me',
            'CopySourceIfMatch' => 'change me',
            'CopySourceIfModifiedSince' => new \DateTimeImmutable(),
            'CopySourceIfNoneMatch' => 'change me',
            'CopySourceIfUnmodifiedSince' => new \DateTimeImmutable(),
            'Expires' => new \DateTimeImmutable(),
            'GrantFullControl' => 'change me',
            'GrantRead' => 'change me',
            'GrantReadACP' => 'change me',
            'GrantWriteACP' => 'change me',
            'Key' => 'change me',
            'Metadata' => ['change me' => 'change me'],
            'MetadataDirective' => 'change me',
            'TaggingDirective' => 'change me',
            'ServerSideEncryption' => 'change me',
            'StorageClass' => 'change me',
            'WebsiteRedirectLocation' => 'change me',
            'SSECustomerAlgorithm' => 'change me',
            'SSECustomerKey' => 'change me',
            'SSECustomerKeyMD5' => 'change me',
            'SSEKMSKeyId' => 'change me',
            'SSEKMSEncryptionContext' => 'change me',
            'CopySourceSSECustomerAlgorithm' => 'change me',
            'CopySourceSSECustomerKey' => 'change me',
            'CopySourceSSECustomerKeyMD5' => 'change me',
            'RequestPayer' => 'change me',
            'Tagging' => 'change me',
            'ObjectLockMode' => 'change me',
            'ObjectLockRetainUntilDate' => new \DateTimeImmutable(),
            'ObjectLockLegalHoldStatus' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '<Bucket>destinationbucket</Bucket>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }

    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html#API_CopyObject_Examples
     */
    public function testSimpleCase(): void
    {
        $input = new CopyObjectRequest([
            'Key' => 'my-second-image.jpg',
            'Bucket' => 'my-bucket',
            'ContentType' => 'image/jpg',
            'CopySource' => '/bucket/my-image.jpg',
        ]);

        self::assertEmpty($input->requestBody());
        $requestHeaders = $input->requestHeaders();
        self::assertEquals('/bucket/my-image.jpg', $requestHeaders['x-amz-copy-source']);

        self::assertEquals('/my-bucket/my-second-image.jpg', $input->requestUri());
    }
}
