<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\AbortMultipartUploadRequest;

class AbortMultipartUploadRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_AbortMultipartUpload.html#API_AbortMultipartUpload_Examples
     */
    public function testRequest(): void
    {
        $input = new AbortMultipartUploadRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'example-object',
            'UploadId' => 'VXBsb2FkIElEIGZvciBlbHZpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZ',
        ]);

        // see example-1.json from SDK
        $expected = '
DELETE /example-bucket/example-object?uploadId=VXBsb2FkIElEIGZvciBlbHZpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZ HTTP/1.1
Content-Type: application/xml
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
