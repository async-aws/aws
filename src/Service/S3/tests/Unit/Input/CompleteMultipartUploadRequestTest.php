<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\CompleteMultipartUploadRequest;
use AsyncAws\S3\ValueObject\CompletedMultipartUpload;
use AsyncAws\S3\ValueObject\CompletedPart;

class CompleteMultipartUploadRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new CompleteMultipartUploadRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',
            'MultipartUpload' => new CompletedMultipartUpload([
                'Parts' => [new CompletedPart([
                    'ETag' => 'change me',
                    'PartNumber' => 1337,
                ])],
            ]),
            'UploadId' => 'change me',
            'RequestPayer' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
                            POST / HTTP/1.0
                            Content-Type: application/xml

                            <Bucket>examplebucket</Bucket>
                        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
