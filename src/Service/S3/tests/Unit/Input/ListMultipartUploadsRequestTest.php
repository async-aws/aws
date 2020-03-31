<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListMultipartUploadsRequest;

class ListMultipartUploadsRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListMultipartUploads.html#API_ListMultipartUploads_Examples
     */
    public function testRequest(): void
    {
        $input = new ListMultipartUploadsRequest([
            'Bucket' => 'example-bucket',
            'Delimiter' => '/',
        ]);

        // see example-1.json from SDK
        $expected = '
GET /example-bucket?uploads&delimiter=/ HTTP/1.1
Content-Type: application/xml
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
