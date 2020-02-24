<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\DeleteObjectRequest;

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

        // see example-1.json from SDK
        $expected = '<Bucket>ExampleBucket</Bucket>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }
}
