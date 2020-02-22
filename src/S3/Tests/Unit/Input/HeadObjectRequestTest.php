<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\S3\Input\HeadObjectRequest;
use PHPUnit\Framework\TestCase;

class HeadObjectRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new HeadObjectRequest([
            'Bucket' => 'change me',
            'IfMatch' => 'change me',
            'IfModifiedSince' => new \DateTimeImmutable(),
            'IfNoneMatch' => 'change me',
            'IfUnmodifiedSince' => new \DateTimeImmutable(),
            'Key' => 'change me',
            'Range' => 'change me',
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
