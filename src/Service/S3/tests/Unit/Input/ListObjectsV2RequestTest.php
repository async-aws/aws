<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListObjectsV2Request;

class ListObjectsV2RequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new ListObjectsV2Request([
            'Bucket' => 'my-bucket',
            'Delimiter' => '/',
            'Prefix' => 'key',
        ]);

        self::assertEquals('/my-bucket?list-type=2', $input->request()->getUri());

        self::assertSame(['delimiter' => '/', 'prefix' => 'key'], $input->request()->getQuery());
    }
}
