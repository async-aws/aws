<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\S3\Input\DeleteObjectRequest;
use PHPUnit\Framework\TestCase;

class DeleteObjectRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new DeleteObjectRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',
            'MFA' => 'change me',
            'VersionId' => 'change me',
            'RequestPayer' => 'change me',
            'BypassGovernanceRetention' => false,
        ]);

        $expected = '<ChangeIt/>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }
}
