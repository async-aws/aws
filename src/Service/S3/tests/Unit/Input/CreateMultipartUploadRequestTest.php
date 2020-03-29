<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\CreateMultipartUploadRequest;

class CreateMultipartUploadRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html#API_CreateMultipartUpload_Examples
     */
    public function testRequest(): void
    {
        $input = new CreateMultipartUploadRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'example-object',
        ]);

        // see example-1.json from SDK
        $expected = '
POST /example-bucket/example-object?uploads HTTP/1.1
Content-Type: application/xml
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
