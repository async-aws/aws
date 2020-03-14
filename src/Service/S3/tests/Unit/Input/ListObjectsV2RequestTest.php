<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListObjectsV2Request;

class ListObjectsV2RequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListObjectsV2Request([
            'Bucket' => 'my-bucket',
            'Delimiter' => '/',
            'Prefix' => 'key',
        ]);

        $expected = '
            GET /my-bucket?delimiter=/&prefix=key&list-type=2 HTTP/1.0
            Content-Type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
