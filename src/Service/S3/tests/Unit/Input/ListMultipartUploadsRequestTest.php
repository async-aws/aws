<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListMultipartUploadsRequest;

class ListMultipartUploadsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListMultipartUploadsRequest([
            'Bucket' => 'change me',
            'Delimiter' => 'change me',
            'EncodingType' => 'change me',
            'KeyMarker' => 'change me',
            'MaxUploads' => 1337,
            'Prefix' => 'change me',
            'UploadIdMarker' => 'change me',
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
