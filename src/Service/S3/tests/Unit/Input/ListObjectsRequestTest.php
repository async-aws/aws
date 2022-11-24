<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListObjectsRequest;

class ListObjectsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListObjectsRequest([
            'Bucket' => 'change me',
            'Delimiter' => 'change me',
            'EncodingType' => 'change me',
            'Marker' => 'change me',
            'MaxKeys' => 1337,
            'Prefix' => 'change me',
            'RequestPayer' => 'change me',
            'ExpectedBucketOwner' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
            GET / HTTP/1.0
            Content-Type: application/xml

            <Bucket>examplebucket</Bucket>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
