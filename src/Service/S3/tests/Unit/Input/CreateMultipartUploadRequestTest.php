<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\CreateMultipartUploadRequest;

class CreateMultipartUploadRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new CreateMultipartUploadRequest([
            'ACL' => 'change me',
            'Bucket' => 'change me',
            'CacheControl' => 'change me',
            'ContentDisposition' => 'change me',
            'ContentEncoding' => 'change me',
            'ContentLanguage' => 'change me',
            'ContentType' => 'change me',
            'Expires' => new \DateTimeImmutable(),
            'GrantFullControl' => 'change me',
            'GrantRead' => 'change me',
            'GrantReadACP' => 'change me',
            'GrantWriteACP' => 'change me',
            'Key' => 'change me',
            'Metadata' => ['change me' => 'change me'],
            'ServerSideEncryption' => 'change me',
            'StorageClass' => 'change me',
            'WebsiteRedirectLocation' => 'change me',
            'SSECustomerAlgorithm' => 'change me',
            'SSECustomerKey' => 'change me',
            'SSECustomerKeyMD5' => 'change me',
            'SSEKMSKeyId' => 'change me',
            'SSEKMSEncryptionContext' => 'change me',
            'RequestPayer' => 'change me',
            'Tagging' => 'change me',
            'ObjectLockMode' => 'change me',
            'ObjectLockRetainUntilDate' => new \DateTimeImmutable(),
            'ObjectLockLegalHoldStatus' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
                            POST / HTTP/1.0
                            Content-Type: application/xml

                            <Bucket>examplebucket</Bucket>
                        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
