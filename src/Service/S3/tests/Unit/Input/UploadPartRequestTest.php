<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\UploadPartRequest;

class UploadPartRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html#API_UploadPart_Examples
     */
    public function testRequest(): void
    {
        $input = new UploadPartRequest([
            'Body' => 'movie',
            'Bucket' => 'example-bucket',
            'ContentMD5' => 'pUNXr/BjKK5G2UKvaRRrOA==',
            'Key' => 'my-movie.m2ts',
            'PartNumber' => 1,
            'UploadId' => 'VCVsb2FkIElEIGZvciBlbZZpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZR',
        ]);

        // see example-1.json from SDK
        $expected = '
PUT /example-bucket/my-movie.m2ts?partNumber=1&uploadId=VCVsb2FkIElEIGZvciBlbZZpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZR HTTP/1.1
Content-MD5: pUNXr/BjKK5G2UKvaRRrOA==

movie
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
