<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\AbortMultipartUploadRequest;

class AbortMultipartUploadRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new AbortMultipartUploadRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',
            'UploadId' => 'change me',
            'RequestPayer' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
                            DELETE / HTTP/1.0
                            Content-Type: application/xml

                            <Bucket>examplebucket</Bucket>
                        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
