<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListObjectVersionsRequest;

class ListObjectVersionsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListObjectVersionsRequest([
            'Bucket' => 'my-bucket',
            'Delimiter' => '/',
            'Prefix' => 'key',
        ]);

        // see example-1.json from SDK
        $expected = '
            GET /my-bucket?delimiter=/&prefix=key&versions= HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
