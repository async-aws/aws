<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListPartsRequest;

class ListPartsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListPartsRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',
            'MaxParts' => 1337,
            'PartNumberMarker' => 1337,
            'UploadId' => 'change me',
            'RequestPayer' => 'change me',
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
