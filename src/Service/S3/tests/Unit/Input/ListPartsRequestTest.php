<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListPartsRequest;

class ListPartsRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html#API_ListParts_Examples
     */
    public function testRequest(): void
    {
        $input = new ListPartsRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'example-object',
            'MaxParts' => 2,
            'PartNumberMarker' => 1,
            'UploadId' => 'XXBsb2FkIElEIGZvciBlbHZpbmcncyVcdS1tb3ZpZS5tMnRzEEEwbG9hZA',
        ]);

        // see example-1.json from SDK
        $expected = '

GET /example-bucket/example-object?uploadId=XXBsb2FkIElEIGZvciBlbHZpbmcncyVcdS1tb3ZpZS5tMnRzEEEwbG9hZA&max-parts=2&part-number-marker=1 HTTP/1.1
Content-Type: application/xml
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
