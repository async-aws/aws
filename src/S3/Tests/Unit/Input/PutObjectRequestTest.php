<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\S3\Input\PutObjectRequest;
use PHPUnit\Framework\TestCase;

class PutObjectRequestTest extends TestCase
{
    public function testConstructWithBody()
    {
        $bucket = 'foo';
        $key = 'bar/baz/biz';
        $body = 'contents';
        $input = new PutObjectRequest([
            'Bucket' => $bucket,
            'Key' => $key,
            'Body' => $body,
        ]);

        $this->assertEquals($bucket, $input->getBucket());
        $this->assertEquals($key, $input->getKey());
        $this->assertEquals($body, $input->getBody());
    }
}
