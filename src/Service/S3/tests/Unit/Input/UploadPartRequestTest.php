<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\UploadPartRequest;

class UploadPartRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new UploadPartRequest([
            'Body' => 'change me',
            'Bucket' => 'change me',
            'ContentLength' => 1337,
            'ContentMD5' => 'change me',
            'Key' => 'change me',
            'PartNumber' => 1337,
            'UploadId' => 'change me',
            'SSECustomerAlgorithm' => 'change me',
            'SSECustomerKey' => 'change me',
            'SSECustomerKeyMD5' => 'change me',
            'RequestPayer' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
                            PUT / HTTP/1.0
                            Content-Type: application/xml

                            <Body>fileToUpload</Body>
                        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
