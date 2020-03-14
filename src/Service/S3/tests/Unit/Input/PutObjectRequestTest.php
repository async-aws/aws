<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutObjectRequest;

class PutObjectRequestTest extends TestCase
{
    public function testRequestBody()
    {
        $bucket = 'foo';
        $key = 'bar/baz/biz';
        $body = 'contents';
        $input = new PutObjectRequest([
            'Bucket' => $bucket,
            'Key' => $key,
            'Body' => $body,
        ]);

        self::assertEquals($bucket, $input->getBucket());
        self::assertEquals($key, $input->getKey());
        self::assertEquals($body, $input->getBody());
        self::assertEquals($body, $input->request()->getBody()->stringify());
    }
}
