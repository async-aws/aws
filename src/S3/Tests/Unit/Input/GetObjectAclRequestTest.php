<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetObjectAclRequest;

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

        // see example-1.json from SDK
        $expected = '<Bucket>examplebucket</Bucket>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }
}
