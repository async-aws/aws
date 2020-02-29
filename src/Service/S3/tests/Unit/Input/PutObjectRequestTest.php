<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutObjectRequest;

class PutObjectRequestTest extends TestCase
{
    public function testConstructWithBody()
    {
        $bucket = 'foo';
        $key = 'bar/baz/biz';
        $body = 'contents';
        $input = new PutObjectRequest([
            'Bucket' => $bucket,
            'Key' => $key,
            'Body' => $body,
        ]);

        self::assertEquals($bucket, $input->getBucket());
        self::assertEquals($key, $input->getKey());
        self::assertEquals($body, $input->getBody());
    }

    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new PutObjectRequest([
            'ACL' => 'change me',
            'Body' => 'change me',
            'Bucket' => 'change me',
            'CacheControl' => 'change me',
            'ContentDisposition' => 'change me',
            'ContentEncoding' => 'change me',
            'ContentLanguage' => 'change me',
            'ContentLength' => 1337,
            'ContentMD5' => 'change me',
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
        $expected = '<Body>HappyFace.jpg</Body>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }
}
