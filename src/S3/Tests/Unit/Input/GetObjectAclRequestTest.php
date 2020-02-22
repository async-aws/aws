<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\S3\Input\GetObjectAclRequest;
use PHPUnit\Framework\TestCase;

class GetObjectAclRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new GetObjectAclRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',
            'VersionId' => 'change me',
            'RequestPayer' => 'change me',
        ]);

        $expected = '<ChangeIt/>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }
}
