<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\S3\Input\GetObjectRequest;
use PHPUnit\Framework\TestCase;

class GetObjectRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new GetObjectRequest([
            'Bucket' => 'change me',
            'IfMatch' => 'change me',
            'IfModifiedSince' => new \DateTimeImmutable(),
            'IfNoneMatch' => 'change me',
            'IfUnmodifiedSince' => new \DateTimeImmutable(),
            'Key' => 'change me',
            'Range' => 'change me',
            'ResponseCacheControl' => 'change me',
            'ResponseContentDisposition' => 'change me',
            'ResponseContentEncoding' => 'change me',
            'ResponseContentLanguage' => 'change me',
            'ResponseContentType' => 'change me',
            'ResponseExpires' => new \DateTimeImmutable(),
            'VersionId' => 'change me',
            'SSECustomerAlgorithm' => 'change me',
            'SSECustomerKey' => 'change me',
            'SSECustomerKeyMD5' => 'change me',
            'RequestPayer' => 'change me',
            'PartNumber' => 1337,
        ]);

        $expected = '<ChangeIt/>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }
}
