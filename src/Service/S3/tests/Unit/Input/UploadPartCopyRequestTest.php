<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\UploadPartCopyRequest;

class UploadPartCopyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UploadPartCopyRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'copy-movie.m2ts',
            'CopySource' => 'example-bucket/my-movie.m2ts',
            'CopySourceRange' => 'bytes=0-1',
            'PartNumber' => 1,
            'UploadId' => 'VCVsb2FkIElEIGZvciBlbZZpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZR',
        ]);

        // see example-1.json from SDK
        $expected = '
            PUT /example-bucket/copy-movie.m2ts?partNumber=1&uploadId=VCVsb2FkIElEIGZvciBlbZZpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZR HTTP/1.1
            Content-Type: application/xml
            x-amz-copy-source: example-bucket/my-movie.m2ts
            x-amz-copy-source-range: bytes=0-1';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
