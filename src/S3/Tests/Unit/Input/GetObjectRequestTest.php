<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetObjectRequest;

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

        // see example-1.json from SDK
        $expected = '<Bucket>examplebucket</Bucket>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }
}
