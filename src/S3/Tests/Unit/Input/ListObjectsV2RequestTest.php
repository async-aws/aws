<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListObjectsV2Request;

class ListObjectsV2RequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new ListObjectsV2Request([
            'Bucket' => 'change me',
            'Delimiter' => 'change me',
            'EncodingType' => 'change me',
            'MaxKeys' => 1337,
            'Prefix' => 'change me',
            'ContinuationToken' => 'change me',
            'FetchOwner' => false,
            'StartAfter' => 'change me',
            'RequestPayer' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '<Bucket>examplebucket</Bucket>';

        self::assertXmlStringEqualsXmlString($expected, $input->requestBody());
    }
}
