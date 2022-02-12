<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListBucketsRequest;

class ListBucketsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListBucketsRequest([
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListBuckets.html
        $expected = '
            GET / HTTP/1.0
            Content-Type: application/xml

        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
