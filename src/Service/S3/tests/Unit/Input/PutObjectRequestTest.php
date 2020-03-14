<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutObjectRequest;

class PutObjectRequestTest extends TestCase
{
    public function testRequest()
    {
        $input = new PutObjectRequest([
            'Bucket' => 'foo',
            'Key' => 'bar/baz/biz',
            'Body' => 'contents',
        ]);

        $expected = '
            PUT /foo/bar/baz/biz HTTP/1.0

            contents
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
